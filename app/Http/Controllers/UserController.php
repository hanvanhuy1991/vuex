<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneContactRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\AuthProfileResource;
use App\Http\Resources\LabelReasonResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\SearchResultUserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;
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
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

        $result = $this->userService->changePassword($request->user(), $currentPassword, $newPassword);

        return $this->responseSuccess(["message" => $result['message']], $result['code']);
    }

    public function index(Request $request)
    {
        $data = UserResource::apiPaginate(User::query(), $request);

        return $this->responseSuccess($data);
    }

    /**
     * Get profile.
     *
     * @param User $user
     *
     * @return integer $status
     * @return object $data
     */
    public function show(User $user)
    {
        return $this->responseSuccess(new ProfileResource($this->userService->getProfile($user)));
    }

    /**
     * Update profile.
     *
     * @param UpdateProfileRequest $request
     * @param User $user
     *
     * @return string $message
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        if ($request->user()->id !== $user->id) {
            return $this->responseError([
                "message" => trans('user.error.user_id_does_not_match')
            ], Response::HTTP_FORBIDDEN, Response::HTTP_FORBIDDEN);
        }

        $data = $request->only([
            'name',
            'birthday',
            'gender',
            'country_code',
            'phone',
            'address',
            'url',
            'description',
            'bio',
            'website'
        ]);

        $avatars = $request->hasFile('avatar') ? $request->file('avatar') : [];
        $covers = $request->hasFile('cover_image') ? $request->file('cover_image') : [];

        return $this->responseSuccess(new ProfileResource($this->userService->updateProfile($data, $user, $avatars, $covers)));
    }

    /**
     * Get list following of user
     *
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowing(User $user, Request $request)
    {
        return $this->responseSuccess(
            SearchResultUserResource::apiPaginate(
                $this->userService->getFollowing($user, $request->get('keyword')),
                $request
            )
        );
    }

    /**
     * Get list follower of user
     *
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollower(User $user, Request $request)
    {
        return $this->responseSuccess(
            SearchResultUserResource::apiPaginate(
                $this->userService->getFollower($user, $request->get('keyword')),
                $request
            )
        );
    }

    /**
     * Get Reason Report
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReasonReport()
    {
        return $this->responseSuccess(LabelReasonResource::collection($this->userService->getReasonReport()));
    }

    /**
     * Action report user
     *
     * @param $user
     * @param ReportRequest $reportRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionReport(User $user, ReportRequest $reportRequest)
    {
        return $this->responseSuccess([
            'model_id' => $this->userService->actionReport($user, $reportRequest->reason_id)
        ]);
    }

    /**
     * Get my profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyProfile()
    {
        return $this->responseSuccess(new AuthProfileResource(Auth::user()));
    }

    /**
     * Update phone contact
     *
     * @param PhoneContactRequest $phoneContactRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoneContact(PhoneContactRequest $phoneContactRequest)
    {
        $data = $phoneContactRequest->phones ?? [];

        return $this->responseSuccess($this->userService->updatePhoneContact($data));
    }

    /**
     * Update location
     *
     * @param LocationRequest $locationRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(LocationRequest $locationRequest)
    {
        $data = $locationRequest->only('location_lat', 'location_long', 'location_name');

        return $this->responseSuccess(new AuthProfileResource($this->userService->updateLocation($data)));
    }

    /**
     * Get suggestion user
     *
     * @param PhoneContactRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestion(PhoneContactRequest $request)
    {
        $phones = $request->phones ?? [];
        $response = $this->userService->suggestion((float)$request->lat, (float)$request->long, $phones);

        return $this->responseSuccess(SearchResultUserResource::apiPaginate($response, $request));
    }
}
