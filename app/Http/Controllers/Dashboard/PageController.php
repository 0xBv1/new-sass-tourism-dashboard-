<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ResourceCreatedEvent;
use App\Models\Page;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PageRequest;
use App\DataTables\PageDataTable;
use App\Models\Translations\PageMetaTranslation;

class PageController extends Controller
{

    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('dashboard.pages.index');
    }


    public function create()
    {
        return view('dashboard.pages.create');
    }


    public function store(PageRequest $request)
    {
        $page = Page::create($request->getSanitized());
        $page->seo()->create($request->get('seo'));
        session()->flash('message', 'Page Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($page);
        return redirect()->route('dashboard.pages.edit', $page);
    }


    public function show(Page $page)
    {
        //
    }


    public function edit(Page $page)
    {
        return view('dashboard.pages.edit', compact('page'));
    }


    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->getSanitized());
        $page->seo ?
            $page->seo->update($request->get('seo')) :
            $page->seo()->create($request->get('seo'));
        foreach ($request->get('meta', []) as $meta_key => $meta_value) {
            $meta = $page->metas()->firstOrNew(['meta_key' => $meta_key]);
            $meta->update($meta_value);
        }
        session()->flash('message', 'Page Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


//    public function destroy(Page $page)
//    {
//        $page->delete();
//        return response()->json([
//            'message' => 'Page Deleted Successfully!'
//        ]);
//    }
}
