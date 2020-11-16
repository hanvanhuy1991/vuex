<?php

namespace App\Services;

use App\Enums\PostEnum;
use App\Enums\CommentEnum;
use App\Enums\ReportEnum;
use App\Notifications\CommentPost;
use App\Notifications\Mention;
use App\Repositories\Reason\LabelReasonRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Post\PostRepository;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentService
{
    /**
     * @var $commentRepository
     * @var $notificationService
     * @var $uploadService
     * @var $userRepository
     * @var $labelReasonRepository
     */
    private $commentRepository;
    private $notificationService;
    private $uploadService;
    private $userRepository;
    private $labelReasonRepository;

    /**
     * CommentService constructor.
     *
     * @param CommentRepositoryInterface $commentRepository
     * @param NotificationService $notificationService
     * @param UploadService $uploadService
     * @param UserRepositoryInterface $userRepository
     * @param LabelReasonRepositoryInterface $labelReasonRepository
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository,
        NotificationService $notificationService,
        UploadService $uploadService,
        UserRepositoryInterface $userRepository,
        LabelReasonRepositoryInterface $labelReasonRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->notificationService = $notificationService;
        $this->uploadService = $uploadService;
        $this->userRepository = $userRepository;
        $this->labelReasonRepository = $labelReasonRepository;
    }

    /**
     * Get paginate comment
     *
     * @param $postId
     * @return array
     */
    public function getCommentForPost($postId)
    {
        return $this->commentRepository->getCommentForPost($postId);
    }

    /**
     * Create comment
     *
     * @param $post
     * @param $request
     *
     * @return array
     *
     * @throws \Exception
     */
    public function store($post, $request)
    {
        $data = $request->only(['content', 'parent_id', 'mentions']);

        if ($request->hasFile('image')) {
            $data['images'] = $this->uploadService->storeS3($request->file('image'), PostEnum::MEDIA_TYPE_IMAGE);
        }
//        if ($request->hasFile('video')) {
//            $data['videos'] = [
//                'url' => $this->uploadService->storeS3($request->file('video'), PostEnum::MEDIA_TYPE_VIDEO),
//                'width' => $request->input('width_video'),
//                'height' => $request->input('height_video')
//            ];
//        }
        $store = $this->commentRepository->store($post, $data);

        $authUser = Auth::user();
        if ($store['status_code'] == Response::HTTP_OK) {
            $mentionDevices = [];
            $replyDevices = [];

            if (!empty($data['mentions'])) {
                unset($data['mentions'][array_search($authUser->id, $data['mentions'])]);
                $users = $this->userRepository->getByIds($data['mentions'])->with('tokenDevices')->get();
                foreach ($users as $user) {
                    $mentionDevices = array_merge($mentionDevices, $user->tokenDevices->pluck('uuid')->toArray());
                }
                if (!empty($mentionDevices)) {
                    $authUser->notify(new Mention($mentionDevices, $authUser, $post, $this->notificationService));
                }
            }

            if (!empty($data['parent_id'])) {
                $comments = $this->commentRepository->findById($data['parent_id']);
                if ($authUser->id != $comments->user_id) {
                    $replyDevices = $comments->user->tokenDevices->pluck('uuid')->toArray();
                    $title = $authUser->name.' '.trans('notification.reply_comment_title');
                    $devices = array_diff($mentionDevices, $replyDevices);
                    if (!empty($devices)) {
                        $authUser->notify(new CommentPost($devices, $authUser, $post, $this->notificationService, $title));
                    }
                }
            }

            if ($authUser->id != $post->created_by &&
                !empty($post->user) &&
                !empty($post->user->notificationSettings()->value('like'))
            ) {
                $commentDevices = $post->user->tokenDevices->pluck('uuid')->toArray();
                $title = $authUser->name.' '.trans('notification.comment_title');
                $devices = array_diff($replyDevices, $commentDevices);
                if (!empty($devices)) {
                    $authUser->notify(new CommentPost($devices, $authUser, $post, $this->notificationService, $title));
                }
            }
        }

        return $store;
    }

    /**
     * Update comment
     *
     * @param $model
     * @param $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($model, $request)
    {
        return $this->commentRepository->updateBasic($model, $request->only(['content', 'parent_id', 'mentions']));
    }

    /**
     * Destroy comment
     *
     * @param $model
     *
     * @return int
     * @throws \Exception
     */
    public function destroy($model)
    {
        DB::beginTransaction();
        try {
            if (!empty($model->images->toArray())) {
                $this->uploadService->deleteS3Files($model->images->pluck('image_path')->toArray());
            }
            if (!empty($model->videos->toArray())) {
                $this->uploadService->deleteS3Files($model->videos->pluck('video_path')->toArray());
            }

            $model->delete();
            $model->videos()->delete();
            $model->images()->delete();
            $model->mentions()->delete();
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * @return collection
     */
    public function getReasonReport()
    {
        return $this->labelReasonRepository->getByType(ReportEnum::REPORT_COMMENT);
    }

    /**
     * Action report
     *
     * @param $comment
     * @param $reasonId
     *
     * @return boolean
     */
    public function actionReport($comment, $reasonId)
    {
        $comment->reports()->create([
            'reportable_type' => ReportEnum::MODEL_COMMENT,
            'user_id' => Auth::id(),
            'reason' => $reasonId,
            'status' => ReportEnum::STATUS_PENDING
        ]);

        return $comment->id;
    }
}
