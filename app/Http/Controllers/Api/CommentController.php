<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Tour;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Get all approved comments",
     *     description="Returns a paginated list of approved tour comments",
     *     @OA\Parameter(
     *         name="tour_id",
     *         in="query",
     *         description="Filter by tour ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         description="Get only featured comments",
     *         @OA\Schema(type="boolean")
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
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="comment", type="string", example="Amazing tour experience!"),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="tour", type="object")
     *             ))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Comment::approved()->with('tour');

        if ($request->has('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        $comments = $query->latest()->paginate($request->per_page ?? 10);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Submit a tour comment/review",
     *     description="Submit a new comment or review for a tour. Comments require approval before being displayed.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tour_id", "name", "email", "comment", "rating"},
     *             @OA\Property(property="tour_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="comment", type="string", maxLength=1000, example="This was an amazing tour experience!"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thank you for your review! It will be visible once approved."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            'tour_id' => $request->tour_id,
            'client_id' => auth('client')->id(),
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'is_approved' => false,
            'is_featured' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review! It will be visible once approved.',
            'data' => new CommentResource($comment),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/tour/{tourSlug}",
     *     tags={"Comments"},
     *     summary="Get comments for a specific tour",
     *     description="Returns paginated approved comments for a specific tour by its slug",
     *     @OA\Parameter(
     *         name="tourSlug",
     *         in="path",
     *         required=true,
     *         description="Tour slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tour not found")
     * )
     */
    public function tourComments(string $tourSlug)
    {
        $tour = Tour::where('slug', $tourSlug)->firstOrFail();

        $comments = $tour->approvedComments()
            ->latest()
            ->paginate(10);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/featured",
     *     tags={"Comments"},
     *     summary="Get featured comments",
     *     description="Returns the latest 10 featured and approved comments with their associated tours",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="comment", type="string", example="Best tour ever!"),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="is_featured", type="boolean", example=true),
     *                 @OA\Property(property="tour", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="slug", type="string")
     *                 )
     *             ))
     *         )
     *     )
     * )
     */
    public function featured()
    {
        $comments = Comment::approved()
            ->featured()
            ->with('tour')
            ->latest()
            ->take(10)
            ->get();

        return CommentResource::collection($comments);
    }
}

