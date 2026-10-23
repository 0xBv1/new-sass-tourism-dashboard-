<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CurrencyDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CurrencyRequest;
use App\Models\Currency;
use Illuminate\Support\Facades\Artisan;

class CurrencyController extends Controller
{

    public function index(CurrencyDataTable $dataTable)
    {
        session()->flash('message', 'Currency rates are updating automatically!');
        return $dataTable->render('dashboard.currencies.index');
    }

    public function create()
    {
        return view('dashboard.currencies.create');
    }


    public function store(CurrencyRequest $request)
    {
        Currency::create($request->getSanitized());
        session()->flash('message', 'Currency Created Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function show(Currency $currency)
    {
        //
    }


    public function edit(Currency $currency)
    {
        return view('dashboard.currencies.edit', compact('currency'));
    }


    public function update(CurrencyRequest $request, Currency $currency)
    {
        $currency->update($request->getSanitized());
        session()->flash('message', 'Currency Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Currency $currency)
    {
        if ($currency->name == 'USD') {
            return response()->json([
                'message' => "Can't delete USD Currency"
            ], 400);
        }
        $currency->delete();
        return response()->json([
            'message' => 'Currency Deleted Successfully!'
        ]);
    }

    public function updateRates()
    {
        Artisan::call('currency:rate');
        return response()->json([
            'message' => 'Currency exchange rates updated successfully!'
        ]);
    }
}
