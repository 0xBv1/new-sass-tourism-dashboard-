<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AccommodationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationPrice;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccommodationDataTable $dataTable)
    {
        return $dataTable->render('dashboard.accommodations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::all();
        return view('dashboard.accommodations.create', compact('hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'nullable|exists:hotels,id',
            'prices' => 'required|array',
        ]);

        $accommodation = Accommodation::create($request->only('name', 'hotel_id'));

        foreach ($request->prices as $group => $price) {
            AccommodationPrice::create([
                'accommodation_id' => $accommodation->id,
                'group' => $group,
                'price' => $price,
            ]);
        }

        return redirect()->route('dashboard.accommodations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        $hotels = Hotel::all();
        $accommodation->load('prices');
        return view('dashboard.accommodations.edit', compact('accommodation', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'nullable|exists:hotels,id',
            'prices' => 'required|array',
        ]);

        $accommodation->update($request->only('name', 'hotel_id'));

        foreach ($request->prices as $group => $price) {
            $accommodation->prices()->updateOrCreate(
                ['group' => $group],
                ['price' => $price]
            );
        }

        return redirect()->route('dashboard.accommodations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return redirect()->route('dashboard.accommodations.index');
    }
}
