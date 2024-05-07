<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    protected $subject;

    protected $view;

    protected $tasks;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $view, $tasks)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->view = $view;
        $this->tasks = $tasks;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Sending email to: ' . $this->email); // tracking log

        Mail::send($this->view, ['tasks' => $this->tasks], function ($message) {
            $message->to($this->email);
            $message->subject($this->subject);
        });
    }
}
