<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BookingRequest;
use App\Models\Booking;

class BookingController extends Controller
{

    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('dashboard.bookings.index');
    }

    public function show(Booking $booking)
    {
        $booking->load(['client', 'currency', 'tours', 'coupon']);

        return view('dashboard.bookings.show', compact('booking'));
    }


    public function update(BookingRequest $request, Booking $booking)
    {
        $booking->update($request->getSanitized());
        session()->flash('message', 'Booking Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }
}
