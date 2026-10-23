<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\NewsLetterDataTable;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterJob;
use App\Models\NewsLetter;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    public function index(NewsLetterDataTable $dataTable)
    {
        return $dataTable->render('dashboard.newsletters.index');
    }

    public function create()
    {
        $subscribersCount = Subscribe::active()->count();
        return view('dashboard.newsletters.create', compact('subscribersCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        NewsLetter::create([
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard.newsletters.index')
            ->with('success', 'Newsletter created successfully.');
    }

    public function edit(NewsLetter $newsletter)
    {
        if (!$newsletter->isDraft()) {
            return back()->with('error', 'Only draft newsletters can be edited.');
        }

        $subscribersCount = Subscribe::active()->count();
        return view('dashboard.newsletters.edit', compact('newsletter', 'subscribersCount'));
    }

    public function update(Request $request, NewsLetter $newsletter)
    {
        if (!$newsletter->isDraft()) {
            return back()->with('error', 'Only draft newsletters can be updated.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $newsletter->update($request->only(['subject', 'body']));

        return redirect()->route('dashboard.newsletters.index')
            ->with('success', 'Newsletter updated successfully.');
    }

    public function destroy(NewsLetter $newsletter)
    {
        if ($newsletter->status === 'sending') {
            return back()->with('error', 'Cannot delete a newsletter that is currently being sent.');
        }

        $newsletter->delete();

        return back()->with('success', 'Newsletter deleted successfully.');
    }

    public function send(NewsLetter $newsletter)
    {
        if (!$newsletter->canBeSent()) {
            return back()->with('error', 'This newsletter cannot be sent.');
        }

        $subscribers = Subscribe::active()->get();
        
        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No active subscribers to send to.');
        }

        $newsletter->update([
            'total_recipients' => $subscribers->count(),
            'status' => 'sending',
        ]);

        // Create logs for each subscriber
        foreach ($subscribers as $subscriber) {
            $newsletter->logs()->create([
                'subscribe_id' => $subscriber->id,
                'status' => 'pending',
            ]);
        }

        // Dispatch the job to send emails
        SendNewsletterJob::dispatch($newsletter);

        return back()->with('success', 'Newsletter is being sent to ' . $subscribers->count() . ' subscribers.');
    }

    public function preview(NewsLetter $newsletter)
    {
        return view('dashboard.newsletters.preview', compact('newsletter'));
    }
}

