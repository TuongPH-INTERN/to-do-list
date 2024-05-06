<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskReminderJob;
use App\Services\Task\GetTaskSendEmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reminder';
    protected $subject = "Don't forget to do task";
    protected $description = 'reminder to do task';
    protected $mailTemplate = 'mail.TaskReminder';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $taskData  = resolve(GetTaskSendEmailService::class)->setParams()->handle();
        Log::info('--------Mail sender job start--------');
        Log::info('List sender: ' . json_encode($taskData));
        foreach ($taskData as $email => $tasks) {
            dispatch(new SendTaskReminderJob($email, $this->subject, $this->mailTemplate, $tasks));
        }
        Log::info('--------Mail sender job end--------');
    }
}
