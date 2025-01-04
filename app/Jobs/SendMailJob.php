<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    /**
     * Create a new job instance.
     */
    public function __construct(public $email, public Mailable $mailable) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send($this->mailable);
    }
}
