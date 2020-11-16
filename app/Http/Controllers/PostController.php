<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Requests\ShareRequest;
use App\Http\Resources\DraftPostResource;
use App\Http\Resources\LabelReasonResource;
use App\Models\Post;
use App\Enums\PostEnum;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Resources\PostResource;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\DetailPostResource;
use App\Http\Resources\CreatedPostResource;
use App\Http\Resources\UpdatedPostResource;

class PostController extends BaseController
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        parent::__construct();

        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $sort = PostEnum::SORT_BY_DEFAULT;
        if (!empty($request->sort) && in_array($request->sort, PostEnum::sorts())) {
            $sort = $request->sort;
        }

        if ($request->type == PostEnum::FOLLOWING) {
            return $this->responseSuccess(DetailPostResource::apiPaginate($this->postService->getPostsByFollow(), $request));
        }

        $response = $request->type == PostEnum::DISCOVERY ?
            $this->postService->getPostByDiscovery($request->priority, $sort) :
            $this->postService->getPostNearby((float)$request->lat, (float)$request->long);

        return $this->responseSuccess(PostResource::apiPaginate($response, $request));
    }

    public function show($slug)
    {
        $response = $this->postService->show($slug);

        return $this->responseSuccess(DetailPostResource::collection($response));
    }

    public function store(CreatePostRequest $request)
    {
        $data = $request->only([
            'title',
            'content',
            'hash_tag_ids',
            'location_name',
            'location_lat',
            'location_long',
            'account_type',
            'media_type',
            'time_public',
            'is_draft'
        ]);
        $imageTags = $request->only('image_tags')['image_tags'] ?? [];

        if (!empty($data['is_draft'])) {
            $data['status'] = PostEnum::STATUS_DRAFT;
        } elseif (!empty($data['time_public'])) {
            $data['status'] = PostEnum::STATUS_PENDING;
        } else {
            $data['status'] = PostEnum::STATUS_ACTIVE;
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $data['media_type'] = PostEnum::MEDIA_TYPE_IMAGE;
        } else {
            $images = null;
        }

        if ($request->hasFile('videos')) {
            $videos = $request->file('videos');
            $data['media_type'] = PostEnum::MEDIA_TYPE_VIDEO;
        } else {
            $videos = null;
        }

        $createdPost = $this->postService->store($data, $images, $videos, $request->user(), $imageTags);

        return $this->responseSuccess([
            'message' => trans('post.created_successfully'),
            'created_post' => new CreatedPostResource($createdPost)
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->only([
            'title',
            'content',
            'hash_tag_ids',
            'location_name',
            'location_lat',
            'location_long',
            'account_type',
            'time_public',
            'deleted_s3_images',
            'deleted_s3_videos',
            'is_draft',
            'time_public'
        ]);
        $imageTags = $request->only('image_tags')['image_tags'] ?? [];

        $timePublic = $post->draftPost->time_public ?? null;

        if (!empty($data['is_draft'])) {
            $data['status'] = PostEnum::STATUS_DRAFT;
        } elseif (!empty($data['time_public']) || strtotime($timePublic) > time()) {
            $data['status'] = PostEnum::STATUS_PENDING;
        } else {
            $data['status'] = PostEnum::STATUS_ACTIVE;
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $data['media_type'] = PostEnum::MEDIA_TYPE_IMAGE;
        } else {
            $images = null;
        }

        if ($request->hasFile('videos')) {
            $videos = $request->file('videos');
            $data['media_type'] = PostEnum::MEDIA_TYPE_VIDEO;
        } else {
            $videos = null;
        }

        $updatedPost = $this->postService->update($data, $images, $videos, $post, $request->user(), $imageTags);

        return $this->responseSuccess([
            'message' => trans('post.updated_successfully'),
            'updated_post' => new UpdatedPostResource($updatedPost)
        ]);
    }

    /**
     * Get posts for user
     *
     * @param $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsByUserId($user, Request $request)
    {
        return $this->responseSuccess(DetailPostResource::apiPaginate($this->postService->getPostsByUserId($user), $request));
    }

    /**
     * Get posts liked for user
     *
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsLikedByUser(User $user, Request $request)
    {
        $response = $this->postService->getPostsLikedByUser($user);

        return $this->responseSuccess(PostResource::apiPaginate($response, $request));
    }

    /**
     * Get posts bookmarked for user
     *
     * @param User $user
     * @param Request $request
     * @param integer $collectionId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsBookmarkedByUser(User $user, Request $request, $collectionId = null)
    {
        $response = $this->postService->getPostsBookmarkedByUser($user, $collectionId);

        return $this->responseSuccess(DetailPostResource::apiPaginate($response, $request));
    }

    /**
     * Share Post
     *
     * @param Post $post
     * @param ShareRequest $shareRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Post $post, ShareRequest $shareRequest)
    {
        $response = $this->postService->share($post, $shareRequest->type);

        return $this->responseSuccess($response);
    }

    /**
     * Get Reason Report
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReasonReport()
    {
        return $this->responseSuccess(LabelReasonResource::collection($this->postService->getReasonReport()));
    }

    /**
     * Action report post
     *
     * @param $post
     * @param ReportRequest $reportRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionReport(Post $post, ReportRequest $reportRequest)
    {
        return $this->responseSuccess([
            'model_id' => $this->postService->actionReport($post, $reportRequest->reason_id)
        ]);
    }

    /**
     * Action hidden post
     *
     * @param $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hidden(Post $post)
    {
        return $this->responseSuccess([
            'model_id' => $this->postService->hidden($post)
        ]);
    }

    public function destroy(Post $post)
    {
        return $this->responseSuccess([
            'model_id' => $this->postService->destroy($post)
        ]);
    }

    public function getDraft(Request $request)
    {
        return $this->responseSuccess(DraftPostResource::apiPaginate($this->postService->getDraft(), $request));
    }
}
