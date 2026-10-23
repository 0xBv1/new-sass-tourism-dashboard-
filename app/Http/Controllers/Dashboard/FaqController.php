<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\FaqDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{

    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('dashboard.faqs.index');
    }


    public function create()
    {
        return view('dashboard.faqs.create');
    }


    public function store(FaqRequest $request)
    {
        $faqs = [];
        $faq = $request->getSanitized();
        unset($faq['tag']);
        if ($tags = $request->get('tag')) {
            foreach (explode(',', $tags) as $tag) {
                $faqs[] = Faq::create(array_merge($faq, ['tag' => trim($tag)]));
            }
        } else {
            $faqs[] = Faq::create($request->getSanitized());
        }
        session()->flash('message', 'Faq Created Successfully!');
        session()->flash('type', 'success');
        foreach ($faqs as $faqData) {
            ResourceCreatedEvent::dispatch($faqData);
        }
        return redirect()->route('dashboard.faqs.edit', $faqs[0]);
    }


    public function show(Faq $faq)
    {
        //
    }


    public function edit(Faq $faq)
    {
        return view('dashboard.faqs.edit', compact('faq'));
    }


    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update($request->getSanitized());
        session()->flash('message', 'Faq Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json([
            'message' => 'Faq Deleted Successfully!'
        ]);
    }
}
