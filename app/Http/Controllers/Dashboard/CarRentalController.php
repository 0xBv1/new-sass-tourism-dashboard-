<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CarRentalDataTable;
use App\Http\Controllers\Controller;
use App\Models\CarRental;

class CarRentalController extends Controller
{
    public function index(CarRentalDataTable $dataTable)
    {
        return $dataTable->render('dashboard.car-rentals.index');
    }

    public function show(CarRental $carRental)
    {
        $carRental->load(['stops', 'destination', 'currency', 'pickup']);

        return view('dashboard.car-rentals.show', compact('carRental'));
    }
}
