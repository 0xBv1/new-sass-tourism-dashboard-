<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\NewsLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 3600;

    public function __construct(
        public NewsLetter $newsletter
    ) {}

    public function handle(): void
    {
        $logs = $this->newsletter->logs()->where('status', 'pending')->with('subscriber')->get();

        foreach ($logs as $log) {
            if (!$log->subscriber || !$log->subscriber->is_active) {
                $log->markAsFailed('Subscriber not found or inactive');
                continue;
            }

            try {
                Mail::to($log->subscriber->email)
                    ->send(new NewsletterMail($this->newsletter, $log->subscriber));

                $log->markAsSent();
                $this->newsletter->increment('sent_count');
            } catch (\Exception $e) {
                $log->markAsFailed($e->getMessage());
                $this->newsletter->increment('failed_count');
            }
        }

        $this->newsletter->markAsSent();
    }
}

