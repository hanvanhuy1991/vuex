<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\UserRole;
use App\Jobs\ForgotPassword;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;

class AuthController extends BaseController
{
    /**
     * @var AuthService
     */
    protected $authService;

     /**
     * @var UserService
     */
    private $userService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * Verify the request and generate tokens.
     *
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->loginAdmin(request(['email', 'password']));
    }


    /**
     * Get profile admin.
     *
     * @param  LoginRequest  $request
     *
     * @return JsonResponse
     */
    public function profile(Request $request)
    {
        $user =  $this->authService->getInformation();
        $now = Carbon::now();
        $created_at = $user->created_at;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'address' => $user->address,
            'birthday' => $user->birthday,
            'avatar' => $user->avatar,
            'created_at' => $created_at->diffForHumans($now),
            'roles' => $user->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return ['name' => $permission->name];
                    }),
                ];
            }),
        ]);
    }

    /**
     * Logout and revoke the token.
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            "status" => Response::HTTP_OK,
            "message" => "Logged out successfully!",
        ]);
    }

    /**
     * Forgot password.
     *
     * @param [string] email
     *
     * @return [json] {status & message}
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->whereHas('roles', function ($q) {
            $q->whereName(UserRole::ADMIN);
        })->first();
        if ($user) {
            $job = (new ForgotPassword($request->email));

            $this->dispatch($job);
        }

        return $this->responseSuccess([
            "message" => trans('auth.send_forgot_password_successfully')
        ]);
    }

     /**
     * Update Profile.
     *
     * @return [json] {status & message}
     */
    public function updateProfile(UpdateProfileRequest $request, $id)
    {
        if ($request->user()->id !== (int)$id) {
            return $this->responseError([
                "message" => trans('user.error.user_id_does_not_match')
            ], Response::HTTP_FORBIDDEN, Response::HTTP_FORBIDDEN);
        }

        $data = $request->only([
            'name',
            'birthday',
            'address',
            'gender',
            'phone',
            'address',
        ]);

        return $this->responseSuccess($this->userService->updateProfile($data, Auth::user()));
    }

    /**
     * Change password.
     *
     * @param ChangePasswordRequest $request
     *
     * @return integer $status
     * @return string $message
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        return $this->responseSuccess(['success' => $user->update(['password' => Hash::make($request->input('new_password'))])]);
    }
}
