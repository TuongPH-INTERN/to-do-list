<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Repositories\User\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CanAccessUpdateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->route('id');
        $authId = Auth::user()->id;

        if (!$request->hasHeader('Authorization')) {                //Login requied
            return response()->json(['message' => __('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }

        switch (Auth::user()->role_id) {
            case Role::ADMIN:
                return $next($request);
                break;

            case Role::STORE:
                $findUser = resolve(UserRepository::class)->find($userId);
                if ($userId == $authId || $findUser->role_id == Role::STAFF) {
                    return $next($request);
                }
                break;

            case Role::STAFF:
                if ($userId == $authId) {
                    return $next($request);
                }
                break;

            default:
                return response()->json(['message' => __('messages.access_denied')], Response::HTTP_FORBIDDEN);
        }

        return response()->json(['message' => __('messages.access_denied')], Response::HTTP_FORBIDDEN);
    }
}
