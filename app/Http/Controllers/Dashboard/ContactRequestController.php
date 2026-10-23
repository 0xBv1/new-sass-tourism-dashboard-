<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ContactRequestDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MarkEmailAsSpamRequest;
use App\Models\ContactRequest;
use App\Models\EmailStatus;

class ContactRequestController extends Controller
{
    public function index(ContactRequestDataTable $dataTable)
    {
        return $dataTable->render('dashboard.contact-requests.index');
    }

    public function markAsSpam(MarkEmailAsSpamRequest $request)
    {
        EmailStatus::updateOrCreate(['email' => $request->get('email')], [
            'email' => $request->get('email'),
            'status' => EmailStatus::SPAM
        ]);

        ContactRequest::where('email', $request->get('email'))->update([
            'spam' => true
        ]);
    }
}
