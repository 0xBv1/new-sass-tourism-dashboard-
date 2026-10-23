<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BlogCommentResource;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/blog-comments",
     *     tags={"Blog"},
     *     summary="Get all approved blog comments",
     *     description="Returns a paginated list of approved blog comments",
     *     @OA\Parameter(
     *         name="blog_id",
     *         in="query",
     *         description="Filter by blog ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="blog_slug",
     *         in="query",
     *         description="Filter by blog slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         description="Get only featured comments",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (default: 10)",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogComment"))
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogComment::approved()->topLevel()->with(['blog', 'client', 'approvedReplies']);

        if ($request->has('blog_id')) {
            $query->where('blog_id', $request->blog_id);
        }

        if ($request->has('blog_slug')) {
            $blog = Blog::where('slug', $request->blog_slug)->first();
            if ($blog) {
                $query->where('blog_id', $blog->id);
            }
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        $comments = $query->latest()->paginate($request->per_page ?? 10);

        return $this->send(BlogCommentResource::collection($comments));
    }

    /**
     * @OA\Post(
     *     path="/api/blog-comments",
     *     tags={"Blog"},
     *     summary="Submit a blog comment",
     *     description="Submit a new comment for a blog post. Comments require approval before being displayed.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"blog_id", "name", "email", "comment"},
     *             @OA\Property(property="blog_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null, description="ID of parent comment for replies"),
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="comment", type="string", maxLength=2000, example="Great article! Very informative.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thank you for your comment! It will be visible once approved."),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogComment")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $comment = BlogComment::create([
            'blog_id' => $request->blog_id,
            'client_id' => auth('client')->id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => false,
            'is_featured' => false,
        ]);

        return $this->send(
            new BlogCommentResource($comment->load(['blog', 'client', 'parent'])),
            'Thank you for your comment! It will be visible once approved.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/blog-comments/blog/{blogSlug}",
     *     tags={"Blog"},
     *     summary="Get comments for a specific blog",
     *     description="Returns paginated approved comments for a specific blog by its slug",
     *     @OA\Parameter(
     *         name="blogSlug",
     *         in="path",
     *         required=true,
     *         description="Blog slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (default: 10)",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogComment"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Blog not found")
     * )
     */
    public function blogComments(string $blogSlug, Request $request): JsonResponse
    {
        $blog = Blog::where('slug', $blogSlug)->firstOrFail();

        $comments = BlogComment::approved()
            ->topLevel()
            ->where('blog_id', $blog->id)
            ->with(['client', 'approvedReplies'])
            ->latest()
            ->paginate($request->per_page ?? 10);

        return $this->send(BlogCommentResource::collection($comments));
    }

    /**
     * @OA\Get(
     *     path="/api/blog-comments/featured",
     *     tags={"Blog"},
     *     summary="Get featured blog comments",
     *     description="Returns the latest 10 featured and approved blog comments with their associated blogs",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogComment"))
     *         )
     *     )
     * )
     */
    public function featured(): JsonResponse
    {
        $comments = BlogComment::approved()
            ->featured()
            ->topLevel()
            ->with(['blog', 'client'])
            ->latest()
            ->take(10)
            ->get();

        return $this->send(BlogCommentResource::collection($comments));
    }

    /**
     * @OA\Post(
     *     path="/api/blog-comments/{id}/reply",
     *     tags={"Blog"},
     *     summary="Reply to a blog comment",
     *     description="Submit a reply to an existing blog comment. Replies require approval before being displayed.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Parent comment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "comment"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
     *             @OA\Property(property="comment", type="string", maxLength=2000, example="I agree with your point!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reply submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thank you for your reply! It will be visible once approved."),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogComment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Parent comment not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function reply(Request $request, BlogComment $blogComment): JsonResponse
    {
        // Ensure the parent comment exists and is approved
        if (!$blogComment->is_approved) {
            return $this->sendError('Cannot reply to an unapproved comment.', 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $reply = BlogComment::create([
            'blog_id' => $blogComment->blog_id,
            'client_id' => auth('client')->id(),
            'parent_id' => $blogComment->id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => false,
            'is_featured' => false,
        ]);

        return $this->send(
            new BlogCommentResource($reply->load(['blog', 'client', 'parent'])),
            'Thank you for your reply! It will be visible once approved.',
            201
        );
    }
}
