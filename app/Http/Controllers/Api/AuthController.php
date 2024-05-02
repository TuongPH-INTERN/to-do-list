<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\User\UserRepository;
use App\Services\Auth\RegisterUserService;
use App\Services\Auth\SetVerifiedEmailService;
use App\Services\User\DeleteUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user with the provided registration request.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $user = resolve(RegisterUserService::class)->setParams($data)->handle();

        if ($user) {
            return response()->json([
                'message' => __('messages.check_mail'),
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => __('messages.error_register')
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Verify email to register account
     *
     * @param Request $request
     * @return Response
     */
    public function verifyEmail(Request $request)
    {
        $data = $request;
        $data['id'] = $request->userID;

        if (!$request->hasValidSignature()) {
            resolve(DeleteUserService::class)->setParams($data)->handle();

            return response()->json([
                'message' => __('messages.invalid_link')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        resolve(SetVerifiedEmailService::class)->setParams($data)->handle();

        return response()->json([
            'message' => __('messages.success_register'),
        ], Response::HTTP_OK);
    }

    /**
     * Login by email and password.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if ($token = Auth::attempt(
            [
                'email' => $data['email'],
                'password' => $data['password'],
            ]
        )) {
            return response()->json([
                'message' => __('messages.success_login'),
                'user' => Auth::user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => __('messages.error_login'),
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Logout account.
     *
     * @param 
     * @return Response
     */
    public function logout()
    {
        try {
            Auth::logout();

            return response()->json(['message' => __('messages.success_logout')]);
        } catch (\Exception $e) {
            Log::error("logout fail", ['result' => $e->getMessage()]);

            return response()->json([
                'message' => __('messages.error_logout')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
