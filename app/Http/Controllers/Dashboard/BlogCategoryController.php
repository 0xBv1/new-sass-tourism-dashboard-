<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ResourceCreatedEvent;
use App\Models\BlogCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BlogCategoryRequest;
use App\DataTables\BlogCategoryDataTable;

class BlogCategoryController extends Controller
{

    public function index(BlogCategoryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.blog-categories.index');
    }


    public function create()
    {
        $categories = BlogCategory::all();
        return view('dashboard.blog-categories.create', compact('categories'));
    }


    public function store(BlogCategoryRequest $request)
    {
        $blogCategory = BlogCategory::create($request->getSanitized());
        $blogCategory->seo()->create($request->get('seo'));
        $blogCategory->relatedTours()->sync($request->get('related_tours'));
        session()->flash('message', 'Blog Category Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($blogCategory);
        return redirect()->route('dashboard.blog-categories.edit', $blogCategory);
    }


    public function show(BlogCategory $blogCategory)
    {
        //
    }


    public function edit(BlogCategory $blogCategory)
    {
        $categories = BlogCategory::where('id', '!=', $blogCategory->id)->get();
        return view('dashboard.blog-categories.edit', compact('blogCategory', 'categories'));
    }


    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory->update($request->getSanitized());

        $blogCategory->seo ? $blogCategory->seo->update($request->get('seo')) :
            $blogCategory->seo()->create($request->get('seo'));
        $blogCategory->relatedTours()->sync($request->get('related_tours'));
        session()->flash('message', 'Blog Category Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return response()->json([
            'message' => 'Blog Category Deleted Successfully!'
        ]);
    }
}
