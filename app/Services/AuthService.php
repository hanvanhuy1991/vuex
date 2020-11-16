<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\User\UserRepositoryInterface;

class AuthService
{
    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Verify the request and generate tokens.
     *
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     */
    public function loginAdmin($credentials)
    {
        $user = Auth::attempt($credentials) ? Auth::user() : false;

        if (!$user || $user->roles->pluck('name')->first() != UserRole::ADMIN) {
            return response()->json([
                "status" => Response::HTTP_UNAUTHORIZED,
                "message" => trans('admin.authentication.information_incorrect')
            ], Response::HTTP_UNAUTHORIZED);
        }
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            "status" => Response::HTTP_OK,
            "message" => "Logged in successfully!!",
            "data" => [
                "access_token" => "$tokenResult->accessToken",
                "token_type" => "Bearer",
                "expires_at" => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        ]);
    }

    /**
     * Get information profile user
     *
     * @return
     */
    public function getInformation()
    {
        return $this->userRepository->getProfileAdmin(Auth::id());
    }
}
