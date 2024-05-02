<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Retrieve information of a user based on email.
     *
     * @param int $email
     * @return App\Models\User|null
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get users, filter with fields.
     *
     * @param $data
     * @return App\Models\User|null
     */
    public function getListUserFilter($data)
    {
        $perPage = $data['per_page'];
        $keyWord = $data['key_word'];

        $query = $this->model->select('*');

        if ($keyWord) {
            $query->where('name', 'LIKE', "%{$keyWord}%")
                ->orWhere('email', 'LIKE', "%{$keyWord}%");
        }

        return $query
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }
}
