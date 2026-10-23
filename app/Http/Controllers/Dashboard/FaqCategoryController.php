<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\FaqCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    public function index(FaqCategoryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.faq-categories.index');
    }

    public function create()
    {
        $locales = config('translatable.locales');
        return view('dashboard.faq-categories.create', compact('locales'));
    }

    public function store(Request $request)
    {
        $locales = config('translatable.locales');
        
        $rules = [
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        foreach ($locales as $locale) {
            $rules["{$locale}.name"] = ($locale === 'en' ? 'required' : 'nullable') . '|string|max:255';
            $rules["{$locale}.description"] = 'nullable|string';
        }

        $request->validate($rules);

        $data = $request->only(['icon', 'order', 'is_active']);

        foreach ($locales as $locale) {
            if ($request->has($locale)) {
                $data[$locale] = $request->input($locale);
            }
        }

        FaqCategory::create($data);

        return redirect()->route('dashboard.faq-categories.index')
            ->with('success', 'FAQ Category created successfully.');
    }

    public function edit(FaqCategory $faqCategory)
    {
        $locales = config('translatable.locales');
        return view('dashboard.faq-categories.edit', compact('faqCategory', 'locales'));
    }

    public function update(Request $request, FaqCategory $faqCategory)
    {
        $locales = config('translatable.locales');
        
        $rules = [
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        foreach ($locales as $locale) {
            $rules["{$locale}.name"] = ($locale === 'en' ? 'required' : 'nullable') . '|string|max:255';
            $rules["{$locale}.description"] = 'nullable|string';
        }

        $request->validate($rules);

        $data = $request->only(['icon', 'order', 'is_active']);

        foreach ($locales as $locale) {
            if ($request->has($locale)) {
                $data[$locale] = $request->input($locale);
            }
        }

        $faqCategory->update($data);

        return redirect()->route('dashboard.faq-categories.index')
            ->with('success', 'FAQ Category updated successfully.');
    }

    public function destroy(FaqCategory $faqCategory)
    {
        // Set faqs to have no category
        $faqCategory->faqs()->update(['faq_category_id' => null]);
        
        $faqCategory->delete();

        return back()->with('success', 'FAQ Category deleted successfully.');
    }
}

