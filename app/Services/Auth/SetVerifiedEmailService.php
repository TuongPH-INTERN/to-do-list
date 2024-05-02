<?php

namespace App\Services\Auth;

use App\Interfaces\User\UserRepositoryInterface;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;


class SetVerifiedEmailService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle()
    {
        try {
            $user = $this->userRepository->find($this->data->id);
            $user->update([
                'email_verified_at' => Carbon::now()
            ]);

            return true;
        } catch (Exception $e) {
            Log::info($e);

            return false;
        }
    }
}
