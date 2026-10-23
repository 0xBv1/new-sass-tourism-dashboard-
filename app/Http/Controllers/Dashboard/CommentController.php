<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CommentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(CommentDataTable $dataTable)
    {
        return $dataTable->render('dashboard.comments.index');
    }

    public function edit(Comment $comment)
    {
        return view('dashboard.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $comment->update($request->only(['is_approved', 'is_featured']));

        return redirect()->route('dashboard.comments.index')
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    public function approve(Comment $comment)
    {
        $comment->approve();

        return back()->with('success', 'Comment approved successfully.');
    }

    public function reject(Comment $comment)
    {
        $comment->reject();

        return back()->with('success', 'Comment rejected successfully.');
    }

    public function toggleFeatured(Comment $comment)
    {
        $comment->toggleFeatured();

        $status = $comment->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Comment {$status} successfully.");
    }
}

