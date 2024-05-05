<?php

namespace App\Repositories\Task;

use App\Interfaces\Task\TaskRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Task;
use App\Enums\TaskStatus;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * Get total, count task by status.
     *
     * @param $userId
     * @return App\Models\Task|null
     */
    public function getTotalTasks($userId)
    {
        return $this->model
            ->selectRaw('COUNT(*) as total_tasks')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS total_doing', [TaskStatus::DOING])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS total_completed', [TaskStatus::COMPLETED])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS total_expired', [TaskStatus::EXPIRED])
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get users, filter with fields, search, sort.
     *
     * @param $data
     * @return App\Models\Task|null
     */
    public function getListTask($data)
    {
        $perPage = $data['per_page'];
        $keyWord = $data['key_word'];
        $userId = $data['user_id'];
        $filter = $data['filter'];
        $sort = $data['sort'];
       
        $query = $this->model->select('*')->where('user_id', $userId);
      
        if ($keyWord) {
            $query->where(function ($query) use ($keyWord) {
                $query->where('task_name', 'LIKE', "%{$keyWord}%")
                ->orWhere('date', 'LIKE', "%{$keyWord}%");
            });
        }
       
        if ($filter) {
           $query->where(function ($query) use ($filter) {
                switch ($filter) {
                    case TaskStatus::DOING:
                        $query = $query->where('status', TaskStatus::DOING);
                        break;
                    case TaskStatus::EXPIRED:
                        $query = $query->where('status', TaskStatus::EXPIRED);
                        break;
                    case TaskStatus::COMPLETED:
                        $query = $query->where('status', TaskStatus::COMPLETED);
                        break;
                }
            });
        }

        if ($sort) {
            switch ($sort) {
                case 'asc':
                    $query->orderBy('date', 'ASC');
                    break;
                case 'desc':
                    $query->orderBy('date', 'DESC');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }
    
        return $query->paginate($perPage);
    }
}
