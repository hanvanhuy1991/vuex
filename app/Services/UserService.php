<?php

namespace App\Services;

use App\Enums\FollowEnum;
use App\Enums\PostEnum;
use App\Enums\ReportEnum;
use App\Repositories\Follow\FollowRepositoryInterface;
use App\Repositories\Interest\InterestRepositoryInterface;
use App\Repositories\Reason\LabelReasonRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var $labelReasonRepository
     */
    private $labelReasonRepository;

    /**
     * @var UploadService
     */
    private $uploadService;

    /**
     * @var $followRepository
     */
    private $followRepository;

    /**
     * @var $interestRepository
     */
    private $interestRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param LabelReasonRepositoryInterface $labelReasonRepository
     * @param UploadService $uploadService
     * @param FollowRepositoryInterface $followRepository
     * @param InterestRepositoryInterface $interestRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        LabelReasonRepositoryInterface $labelReasonRepository,
        UploadService $uploadService,
        FollowRepositoryInterface $followRepository,
        InterestRepositoryInterface $interestRepository
    ) {
        $this->userRepository = $userRepository;
        $this->labelReasonRepository = $labelReasonRepository;
        $this->uploadService = $uploadService;
        $this->followRepository = $followRepository;
        $this->interestRepository = $interestRepository;
    }

    public function changePassword($user, $currentPassword, $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            $code = config('api.code.password_does_not_match');
            $message = trans('user.error.password_does_not_match');
        } elseif (strcmp($currentPassword, $newPassword) == 0) {
            $code = config('api.code.same_password');
            $message = trans('user.error.same_password');
        } else {
            $code = Response::HTTP_OK;
            $user->update(['password' => Hash::make($newPassword)]);

            $message = trans('user.change_password_successfully');
        }

        return ['code' => $code, 'message' => $message];
    }

    public function getProfile($user)
    {
        return $this->userRepository->getProfile($user);
    }

    public function updateProfile($data, $user, $avatars = null, $covers = null)
    {
        if (!empty($avatars)) {
            if (!empty($user->avatar)) {
                $this->uploadService->deleteS3Files($user->avatar);
            }
            $data['avatar'] = $this->uploadService->storeS3($avatars, PostEnum::MEDIA_TYPE_VIDEO);
        }

        if (!empty($covers)) {
            if (!empty($user->cover_image)) {
                $this->uploadService->deleteS3Files($user->cover_image);
            }
            $data['cover_image'] = $this->uploadService->storeS3($covers, PostEnum::MEDIA_TYPE_VIDEO);
        }

        $this->userRepository->update($user, $data);

        return $this->userRepository->getProfile($user);
    }

    /**
     * Get list following of user
     *
     * @param $user
     * @param $keyword
     *
     * @return builder
     */
    public function getFollowing($user, $keyword)
    {
        $userIds = $user->followers->pluck('follower_id')->toArray();
        $query = $this->userRepository->getByIds($userIds);
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.$keyword.'%');
        }

        return $query->withCount('followers');
    }

    /**
     * Get list follower of user
     *
     * @param $user
     * @param $keyword
     *
     * @return builder
     */
    public function getFollower($user, $keyword)
    {
        $userIds = $user->following->pluck('is_followed_id')->toArray();
        $query = $this->userRepository->getByIds($userIds);
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.$keyword.'%');
        }

        return $query->withCount('followers');
    }

    /**
     * @return collection
     */
    public function getReasonReport()
    {
        return $this->labelReasonRepository->getByType(ReportEnum::REPORT_USER);
    }

    /**
     * Action report
     *
     * @param $user
     * @param $reasonId
     *
     * @return boolean
     */
    public function actionReport($user, $reasonId)
    {
        $user->reports()->create([
            'reportable_type' => ReportEnum::MODEL_USER,
            'user_id' => Auth::id(),
            'reason' => $reasonId,
            'status' => ReportEnum::STATUS_PENDING
        ]);

        return $user->id;
    }

    /**
     * Update phone contact
     *
     * @param $data
     *
     * @return mixed
     */
    public function updatePhoneContact($data)
    {
        $phoneContacts = Auth::user()->phoneContacts();
        if (!empty($data)) {
            $inserts = [];
            foreach ($data as $value) {
                $inserts[] = [
                    'phone_number' => $value
                ];
            }

            $phoneContacts->delete();
            $phoneContacts->createMany($inserts);
        }

        return Auth::user()->phoneContacts;
    }

    /**
     * Update location
     *
     * @param $data
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function updateLocation($data)
    {
        Auth::user()->update($data);

        return Auth::user();
    }

    /**
     * Get suggestion user
     *
     * @param $lat
     * @param $long
     * @param $phones
     *
     * @return mixed
     */
    public function suggestion($lat, $long, $phones)
    {
        $authUser = Auth::user();
        $followings = $authUser->following()->whereStatus(FollowEnum::STATUS_ACTIVE)->pluck('is_followed_id')->toArray();
        $idByFollowings = $this->followRepository->getByFollowerIds($followings)->pluck('is_followed_id')->toArray();

        $interests = $authUser->interests->pluck('interested_in')->toArray();
        $idByInterests = $this->interestRepository->getByInterestedInIds($interests)->pluck('user_id')->toArray();

        $validIds = array_unique(array_merge($idByFollowings, $idByInterests));

        $phoneNumbers = $authUser->phoneContacts->pluck('phone_number')->toArray();
        $phoneNumbers = array_unique(array_merge($phoneNumbers, $phones));
        $this->updatePhoneContact($phoneNumbers);

        return $this->userRepository->suggestion($lat, $long, $validIds, $followings, $phoneNumbers);
    }
}
