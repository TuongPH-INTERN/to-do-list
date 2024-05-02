<?php

namespace App\Services\User;

use App\Interfaces\User\UserRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Log;

class GetUserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle()
    {
        try {
            $perPage = $this->data->per_page ?? 5;

            if (!is_numeric($perPage) || $perPage <= 0) {
                $perPage = 5;
            }

            $keyWord = htmlspecialchars($this->data->key_word) ?? null;

            $data = [
                'key_word' => $keyWord,
                'per_page' => $perPage,
            ];

            return $this->userRepository->getListUserFilter($data);
        } catch (Exception $e) {
            Log::info($e);

            return false;
        }
    }
}
