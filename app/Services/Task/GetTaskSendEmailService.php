<?php

namespace App\Services\Task;

use App\Interfaces\Task\TaskRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Log;

class GetTaskSendEmailService extends BaseService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle()
    {
        try {
            $list = $this->taskRepository->getListUserReminderMail();

            $tasksByEmail = [];

            foreach ($list as $task) {
                $email = $task->email;

                if (!array_key_exists($email, $tasksByEmail)) {
                    $tasksByEmail[$email] = [];
                }

                $tasksByEmail[$email][] = $task->task_name;
            }

            return $tasksByEmail;
        } catch (Exception $e) {
            Log::info($e);

            return false;
        }
    }
}
