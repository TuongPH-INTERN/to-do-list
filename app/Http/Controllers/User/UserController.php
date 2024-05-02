<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\GetUserService;
use App\Services\User\ShowUserService;
use App\Services\User\UpdateUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = resolve(GetUserService::class)->setParams($request)->handle();

        return $this->responseSuccess([
            'message' => __('messages.success'),
            'users' => $users,
        ]);
    }

    /**
     * Create user.
     *
     * @param CreateUserRequest $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);

        $user = resolve(CreateUserService::class)->setParams($data)->handle();

        if ($user) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
                'user' => $user,
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }

    /**
     * Show user by ID.
     *
     * @param int $userId
     * @return Response
     */
    public function show(int $userId)
    {
        $user = resolve(ShowUserService::class)->setParams($userId)->handle();

        return $this->responseSuccess([
            'message' => __('messages.success'),
            'users' => $user
        ]);
    }

    /**
     * Update user's information.
     *
     * @param UpdateUserRequest $request
     * @param int $userId
     * @return Response
     */
    public function update(UpdateUserRequest $request, int $userId)
    {
        $data['information'] = $request->validated();
        $data['id'] = $userId;

        $user = resolve(UpdateUserService::class)->setParams($data)->handle();

        if ($user) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
                'users' => $user
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }

    /**
     * Delete user by ID.
     *
     * @param int $userId
     * @return Response
     */
    public function destroy(int $userId)
    {
        $user = resolve(DeleteUserService::class)->setParams($userId)->handle();

        if ($user) {
            return $this->responseSuccess([
                'message' => __('messages.success'),
            ]);
        }

        return $this->responseErrors(__('messages.error'));
    }
}
