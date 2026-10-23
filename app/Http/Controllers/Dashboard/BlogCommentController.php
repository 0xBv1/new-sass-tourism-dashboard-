<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\BlogCommentDataTable;
use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function index(BlogCommentDataTable $dataTable)
    {
        return $dataTable->render('dashboard.blog-comments.index');
    }

    public function edit(BlogComment $blogComment)
    {
        $blogComment->load(['blog', 'parent', 'replies']);
        return view('dashboard.blog-comments.edit', compact('blogComment'));
    }

    public function update(Request $request, BlogComment $blogComment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $blogComment->update($request->only([
            'name',
            'email',
            'comment',
            'is_approved',
            'is_featured',
        ]));

        return redirect()->route('dashboard.blog-comments.index')
            ->with('success', 'Blog comment updated successfully.');
    }

    public function destroy(BlogComment $blogComment)
    {
        $blogComment->delete();

        return back()->with('success', 'Blog comment deleted successfully.');
    }

    public function approve(BlogComment $blogComment)
    {
        $blogComment->approve();

        return back()->with('success', 'Blog comment approved successfully.');
    }

    public function reject(BlogComment $blogComment)
    {
        $blogComment->reject();

        return back()->with('success', 'Blog comment rejected successfully.');
    }

    public function toggleFeatured(BlogComment $blogComment)
    {
        $blogComment->toggleFeatured();

        $status = $blogComment->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Blog comment {$status} successfully.");
    }

    public function createReply(BlogComment $blogComment)
    {
        $blogComment->load('blog');
        return view('dashboard.blog-comments.create-reply', compact('blogComment'));
    }

    public function storeReply(Request $request, BlogComment $blogComment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'is_approved' => 'boolean',
        ]);

        $reply = BlogComment::create([
            'blog_id' => $blogComment->blog_id,
            'parent_id' => $blogComment->id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'is_approved' => $request->has('is_approved') ? (bool)$request->is_approved : false,
            'is_featured' => false,
        ]);

        return redirect()->route('dashboard.blog-comments.edit', $blogComment)
            ->with('success', 'Reply created successfully.');
    }
}
