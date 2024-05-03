<?php

namespace App\Repositories\Task;

use App\Interfaces\Task\TaskRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Task;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * Get users, filter with fields.
     *
     * @param $data
     * @return App\Models\User|null
     */
    public function getListTask($data)
    {
        $perPage = $data['per_page'];
        $keyWord = $data['key_word'];
        $userId = $data['user_id'];

        $query = $this->model->select('*')->where('user_id', $userId);

        if ($keyWord) {
            $query->where('name', 'LIKE', "%{$keyWord}%")
                ->orWhere('email', 'LIKE', "%{$keyWord}%");
        }

        return $query
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }
}
