<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\BlogStatus;
use App\Events\ResourceCreatedEvent;
use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BlogRequest;
use App\DataTables\BlogDataTable;
use App\Models\BlogCategory;

class BlogController extends Controller
{

    public function index(BlogDataTable $dataTable)
    {
        return $dataTable->render('dashboard.blogs.index');
    }


    public function create()
    {
        $categories = BlogCategory::all();
        return view('dashboard.blogs.create', compact('categories'));
    }


    public function store(BlogRequest $request)
    {
        $blog = Blog::create($request->getSanitized());
        $blog->seo()->create($request->get('seo'));
        $blog->relatedTours()->sync($request->get('related_tours'));
        $blog->categories()->attach($request->get('categories'));
        session()->flash('message', 'Blog Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($blog);
        return redirect()->route('dashboard.blogs.edit', $blog);
    }


    public function show(Blog $blog)
    {
        //
    }


    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        return view('dashboard.blogs.edit', compact('blog', 'categories'));
    }


    public function update(BlogRequest $request, Blog $blog)
    {
        $blog->update($request->getSanitized());
        if ($request->filled('action')) {
            return response()->json([
                'message' => 'Blog Updated Successfully'
            ]);
        }
        $blog->seo ?
            $blog->seo->update($request->get('seo')) :
            $blog->seo()->create($request->get('seo'));
        $blog->categories()->sync($request->get('categories'));
        $blog->relatedTours()->sync($request->get('related_tours'));
        session()->flash('message', 'Blog Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Blog $blog)
    {
        $blog->delete();
        return response()->json([
            'message' => 'Blog Deleted Successfully!'
        ]);
    }
}
