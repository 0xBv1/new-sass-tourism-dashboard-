<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CustomTripDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AssignCustomTripRequest;
use App\Mail\Admin\AssignedCustomTripMail;
use App\Models\CustomTrip;
use App\Models\User;
use App\Notifications\Admin\AssignedCustomTripNotification;
use Illuminate\Support\Facades\Mail;

class CustomTripController extends Controller
{
    public function index(CustomTripDataTable $dataTable)
    {
        return $dataTable->render('dashboard.custom-trips.index');
    }


    public function show(CustomTrip $customTrip)
    {
        $users = User::select(['id', 'name', 'email'])->where('id', '!=', admin()->id)->get();
        abort_if(
            !auth()->user()->hasRole('Administrator') && $customTrip->assigned_operator_id != auth()->id(),
            403
        );
        return view('dashboard.custom-trips.show', compact('customTrip', 'users'));
    }


    public function assign(AssignCustomTripRequest $request, CustomTrip $customTrip)
    {
        $customTrip->update($request->getSanitized());
        if ($customTrip->wasChanged('assigned_operator_id')) {
            try_exec(fn() => Mail::to($customTrip->operator->email)->send(new AssignedCustomTripMail($customTrip, $customTrip->operator)));
            if ($customTrip->operator->phone_code && $customTrip->operator->phone) {
                try_exec(fn() => $customTrip->operator->notify(new AssignedCustomTripNotification($customTrip)));
            }
        }
        session()->flash('type', 'success');
        session()->flash('message', 'Request assigned to operator successfully');
        return back();
    }
}
