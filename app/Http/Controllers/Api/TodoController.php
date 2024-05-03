<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\Task\CreateTaskService;
use App\Services\Task\DeleteTaskService;
use App\Services\Task\GetTaskService;
use App\Services\Task\ShowTaskService;
use App\Services\Task\UpdateTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Get all users.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tasks = resolve(GetTaskService::class)->setParams($request)->handle();

        return $this->responseSuccess([
            'message' => __('messages.success'),
            'tasks' => $tasks,
        ]);
    }

    /**
     * Create user.
     *
     * @param CreateUserRequest $request
     * @return Response
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        $task = resolve(CreateTaskService::class)->setParams($data)->handle();

        if ($task) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
                'task' => $task,
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }

    /**
     * Show task by ID.
     *
     * @param int $taskId
     * @return Response
     */
    public function show(int $taskId)
    {
        $task = resolve(ShowTaskService::class)->setParams($taskId)->handle();

        return $this->responseSuccess([
            'message' => __('messages.success'),
            'task' => $task
        ]);
    }

    /**
     * Update task's content.
     *
     * @param UpdateUserRequest $request
     * @param int $taskId
     * @return Response
     */
    public function update(TaskRequest $request, int $taskId)
    {
        $data['information'] = $request->validated();
        $data['id'] = $taskId;

        $task = resolve(UpdateTaskService::class)->setParams($data)->handle();

        if ($task) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
                'task' => $task
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }

    /**
     * Delete task by ID.
     *
     * @param int $taskId
     * @return Response
     */
    public function destroy(int $taskId)
    {
        $task = resolve(DeleteTaskService::class)->setParams($taskId)->handle();

        if ($task) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }
}
