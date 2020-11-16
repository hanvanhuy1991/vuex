<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\UserRole;
use App\Jobs\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UniqueEmailRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * Create new user.
     *
     * @param [string] name
     * @param [string] email
     * @param [datetime] birthday
     * @param [string] password
     * @param [string] password_confirmation
     * @param [integer] gender
     * @param [array] favorites
     * @param [string] token_device
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(SignupRequest $request)
    {
        DB::beginTransaction();
        try {
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $avatars = [
                $protocol.config('key.domain').'/images/female.jpg',
                $protocol.config('key.domain').'/images/male.jpg'
            ];
            $avatar = $request->gender == UserRole::OTHER ? $avatars[mt_rand(0, (count($avatars) - 1))] : $avatars[$request->gender];
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'password' => bcrypt($request->password),
                'status' => UserRole::ACTIVE,
                'avatar' => $avatar,
                'cover_image' => $protocol.config('key.domain').'/images/cover_image.jpg'
            ]);
            $user->assignRole(UserRole::UNICER);
            $user->save();
            $insertInterestedId = collect($request->interested_in)->transform(function ($item) {
                return [
                    'interested_in' => $item,
                ];
            })->toArray();

            $tokenDevice = collect($request->token_device)->transform(function ($item) {
                return [
                    'uuid' => $item,
                ];
            })->toArray();

            $userRelation =  User::find($user->id);
            $userRelation->interests()->createMany($insertInterestedId);
            $userRelation->tokenDevices()->createMany($tokenDevice);
            $userRelation->notificationSettings()->create([
                'like' => true,
                'comment' => true,
                'follow' => true,
                'message' => true,
            ]);
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            DB::commit();

            return response()->json([
                "status" => Response::HTTP_OK,
                "message" => trans('user.create_successfully'),
                "data" => [
                    "access_token" => "Bearer $tokenResult->accessToken",
                    "token_type" => "Bearer",
                    "expires_at" => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            report($ex);

            return false;
        }
    }

    /**
     * Login and create token.
     *
     * @param [string] email
     * @param [string] password
     * @param [boolean] remember_me
     * @param [string] token_device
     *
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(LoginRequest $request)
    {
        $tokenDevice = $request->token_device;
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "status" => Response::HTTP_UNAUTHORIZED,
                "message" => "Unauthorized!"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $arrayTokenDevices = $user->tokenDevices->pluck('uuid')->toArray();

        if (!in_array($tokenDevice, $arrayTokenDevices)) {
            $tokenDevice = collect($tokenDevice)->transform(function ($item) {
                return [
                    'uuid' => $item,
                ];
            })->toArray();

            $user->tokenDevices()->createMany($tokenDevice);
        }

        return response()->json([
            "status" => Response::HTTP_OK,
            "message" => "Logged in successfully!!",
            "data" => [
                "access_token" => "Bearer $tokenResult->accessToken",
                "token_type" => "Bearer",
                "expires_at" => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        ]);
    }

    /**
     * Logout and revoke the token.
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->responseSuccess([
            "message" => "Logged out successfully!"
        ]);
    }

    public function checkEmail(UniqueEmailRequest $request)
    {
        return response()->json([
            "status" => Response::HTTP_OK,
            'message' => trans('user.no_exist_email')
        ], 200);
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
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $job = (new ForgotPassword($request->email));

            $this->dispatch($job);

            return $this->responseSuccess([
                "message" => trans('auth.send_forgot_password_successfully')
            ]);
        } else {
            return $this->responseError([
                "message" => trans('auth.email_not_found')
            ], Response::HTTP_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }
    }
}
