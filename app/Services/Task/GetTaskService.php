<?php

namespace App\Services\Task;

use App\Enums\TaskStatus;
use App\Interfaces\Task\TaskRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetTaskService extends BaseService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle()
    {
        try {
            $perPage = $this->data->per_page ?? 5;
            $userId = $this->data->user_id ?? Auth::user()->id;

            if (!is_numeric($perPage) || $perPage <= 0) {
                $perPage = 5;
            }

            $keyWord = htmlspecialchars($this->data->key_word) ?? null;

            $data = [
                'key_word' => $keyWord,
                'per_page' => $perPage,
                'user_id' => $userId,
            ];

            $tasks =  $this->taskRepository->getListTask($data);

            foreach ($tasks as $task) {
                if (strtotime($task->date) < strtotime(now()) && $task->status === TaskStatus::DOING) {
                    $task->update(['status' => TaskStatus::EXPIRED]);
                }
            }

            return $tasks;
        } catch (Exception $e) {
            Log::info($e);

            return false;
        }
    }
}
