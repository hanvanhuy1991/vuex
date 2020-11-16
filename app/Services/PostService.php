<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Enums\LikeEnum;
use App\Enums\PostEnum;
use App\Enums\PointEnum;
use App\Enums\HiddenEnum;
use App\Enums\ReportEnum;
use App\Enums\LanguageEnum;
use App\Enums\FavoriteEnum;
use App\Jobs\UploadFilePost;
use App\Enums\CollectionEnum;
use LanguageDetection\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\Video\VideoRepositoryInterface;
use App\Repositories\Topic\TopicRepositoryInterface;
use App\Repositories\Reason\LabelReasonRepositoryInterface;

class PostService
{
    protected $uploadService;
    protected $postRepository;
    protected $imageRepository;
    protected $videoRepository;
    protected $topicRepository;
    protected $notificationService;
    protected $labelReasonRepository;
    protected $userRepository;

    public function __construct(
        UploadService $uploadService,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TopicRepositoryInterface $topicRepository,
        ImageRepositoryInterface $imageRepository,
        VideoRepositoryInterface $videoRepository,
        NotificationService $notificationService,
        LabelReasonRepositoryInterface $labelReasonRepository
    ) {
        $this->uploadService = $uploadService;
        $this->postRepository = $postRepository;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
        $this->topicRepository = $topicRepository;
        $this->notificationService = $notificationService;
        $this->labelReasonRepository = $labelReasonRepository;
        $this->userRepository = $userRepository;
    }

    public function getPostsByFollow()
    {
        $user = Auth::user();
        $followerIds = $user->following()->pluck('is_followed_id')->toArray();
        array_push($followerIds, $user->id);

        return $this->postRepository->getPostsUserIds($followerIds)->orderBy('action_time', 'DESC');
    }

    /**
     * Get post by discovery.
     * 1. Get $hashTagIds and $topicIds by last search
     * 2. Get $hashTagIds and $topicIds by last action (like, share, comment, bookmark)
     * 3. Merge $hashTagIds (1)(2) and $topicIds(1)(2)
     * 4. Unique $hashTagIds and $topicIds in (3)
     * 5. Get $postIds by $hashTagIds in (4)
     * 6. If $priority is second => $postIds is diff $prioritySecondPostIds and $postIds in (5)
     *
     * @param [string] $priority in [first, second]
     * @param [string] $sort
     *
     * @return \App\Repositories\Post\bulider
     */
    public function getPostByDiscovery($priority, $sort)
    {
        $user = Auth::user();
        $interests = $user->interests->pluck('interested_in')->toArray();

        $topicIdsBySearch = $this->getDataByLastSearch($user)['topic_ids'];
        $categoryIdsBySearch = $this->getDataByLastSearch($user)['category_ids'];

        $topicIdsByLastAction = $this->getDataByLastAction($user)['topic_ids'];
        $categoryIdsByLastAction = $this->getDataByLastAction($user)['category_ids'];

        $topicIds = array_unique(array_merge($topicIdsBySearch, $topicIdsByLastAction));
        $categoryIds = array_unique(array_merge($categoryIdsBySearch, $categoryIdsByLastAction, $interests));

        $hashTagIdsByTopicIds = $this->topicRepository->getByCategoryIds($categoryIds)->pluck('id')->toArray();
        if ($sort == PostEnum::SORT_BY_DEFAULT) {
            $postIds = $this->getPostIdsByTopicIds($topicIds);
            if ($priority == PostEnum::SECOND) {
                $topicIds = array_diff($hashTagIdsByTopicIds, $topicIds);
                $prioritySecondPostIds = $this->getPostIdsByTopicIds($topicIds);
                $postIds = array_diff($prioritySecondPostIds, $postIds);
            }
        } else {
            $postIds = $this->getPostIdsByTopicIds($hashTagIdsByTopicIds);
        }

        return $this->postRepository->getPostByDiscovery($postIds, $sort);
    }

    /**
     * Get post nearby
     *
     * @param $lat
     * @param $long
     *
     * @return builder
     */
    public function getPostNearby($lat, $long)
    {
        return $this->postRepository->getPostNearby($lat, $long);
    }

    /**
     * Get topicIds and HashTagIds
     *
     * @param [model] $user
     * @return array
     */
    private function getDataByLastSearch($user)
    {
        $topicIds = [];
        $categoryIds = [];
        $lastSearchContent = $user->lastSearch()->latest()->value('search_content');
        if (!empty($lastSearchContent)) {
            $datas = $this->postRepository->getHashTagsBySearch($lastSearchContent);
            foreach ($datas as $data) {
                $topicIds = array_merge($topicIds, $data->hashTags->pluck('id')->toArray());
                $categoryIds = array_merge($categoryIds, $data->hashTags->pluck('topic_id')->toArray());
            }
        }

        return [
            'topic_ids' => $topicIds,
            'category_ids' => $categoryIds,
        ];
    }

    /**
     * Get postIds by HashTag
     *
     * @param [model] $user
     *
     * @return array
     */
    private function getDataByLastAction($user)
    {
        $topicIds = [];
        $categoryIds = [];
        $datas = $user->lastActions()->latest()->limit(PostEnum::LIMIT)->with('post.hashTags')->get();
        foreach ($datas as $data) {
            $topicIds = array_merge($topicIds, $data->post->hashTags->pluck('id')->toArray());
            $categoryIds = array_merge($categoryIds, $data->post->hashTags->pluck('topic_id')->toArray());
        }

        return [
            'topic_ids' => $topicIds,
            'category_ids' => $categoryIds,
        ];
    }

    /**
     * Get postIds by HashTag
     *
     * @param [array] $hashTagIds
     *
     * @return array
     */
    private function getPostIdsByTopicIds($topicIds)
    {
        $topics = $this->topicRepository->getByIds($topicIds)->with('posts')->get();
        $postIds = [];
        foreach ($topics as $topic) {
            $postIds = array_merge($postIds, $topic->posts->pluck('id')->toArray());
        }

        return array_unique($postIds);
    }

    public function show($slug)
    {
        return $this->postRepository->show($slug);
    }

    /**
     * Create post.
     * 1. Store basic information in table "posts".
     * 2. Store data in pivot table "hash_tag_post".
     * 3. Store images/videos in S3.
     * 4. Store images/videos information in table images/videos.
     *
     * @param array $data
     * @param array $images
     * @param array $videos
     * @param User $user
     * @param array $imageTags
     *
     * @return string $message
     *
     * @throws string $message
     */
    public function store($data, $images, $videos, $user, $imageTags)
    {
        DB::beginTransaction();

        try {
            $post = $this->storeBasic($data, $user);

            if (isset($data['hash_tag_ids'])) {
                $this->storeHashTags($post, $data['hash_tag_ids'], $user->id);
            }

            if (!is_null($images)) {
                $imageTags = array_column($imageTags, 'tags', 'image_name');
                $this->storeFiles($images, PostEnum::MEDIA_TYPE_IMAGE, $post, $user, $imageTags);
            }

            if (!is_null($videos)) {
                $this->storeFiles($videos, PostEnum::MEDIA_TYPE_VIDEO, $post, $user);
            }

            if (!empty($data['time_public'])) {
                $this->storeDraftPost($post, $data['time_public']);
            }

            DB::commit();

            return $this->postRepository->findById($post->id);
        } catch (\Exception $e) {
            DB::rollback();

            report($e);

            return trans('post.created_failed');
        }
    }

    public function storeFiles($files, $type, $post, $user, $imageTags = [])
    {
        try {
            if (isset($files)) {
                $filePaths = [];
                $widths = [];
                $heights = [];
                $gif = [];
                $insertImageTags = [];

                foreach ($files as $file) {
                    $response = $this->uploadService->storeLocal($file, 'local', $type);

                    $url = $type == PostEnum::MEDIA_TYPE_VIDEO ? $response['url_video'] : $response['url'];
                    $filePaths[] = $url;

                    if ($type == PostEnum::MEDIA_TYPE_IMAGE) {
                        $fileName = $file->getClientOriginalName();
                        $widths[] = $response['width'];
                        $heights[] = $response['height'];
                        if (array_key_exists($fileName, $imageTags)) {
                            $insertImageTags[$url] = $imageTags[$fileName];
                        }
                    } else {
                        $widths[] = $response['width'];
                        $heights[] = $response['height'];
                        $gif[] = $response['url_gif'];
                    }
                }

                UploadFilePost::dispatch($this->uploadService, $filePaths, $post, $user->id, $widths, $heights, $gif, $insertImageTags);
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function storeBasic($data, $user)
    {
        return $this->postRepository->create([
            'title' => $data['title'] ?? null,
            'content' => $data['content'] ?? null,
            'location_name' => $data['location_name'] ?? null,
            'location_lat' => $data['location_lat'] ?? null,
            'location_long' => $data['location_long'] ?? null,
            'thumbnail' => $data['thumbnail'] ?? null,
            'thumbnail_width' => $data['thumbnail_width'] ?? null,
            'thumbnail_height' => $data['thumbnail_height'] ?? null,
            'media_type' => $data['media_type'] ?? null,
            'account_type' => $user->roles()->pluck('id')->first() ?? null,
            'status' => $data['status'] ?? null,
            'created_by' => $user->id,
            'action_time' => date('Y-m-d H:i:s'),
            'current_language' => $this->detectLanguage($data['content']),
            'view' => 0
        ]);
    }

    private function detectLanguage($string)
    {
        if (empty($string)) {
            return LanguageEnum::ENGLISH;
        }
        $language = new Language();
        $result = $language->detect($string)->limit(0, 1)->__toString();

        return $result == LanguageEnum::JAPANESE ? LanguageEnum::JAPANESE : LanguageEnum::ENGLISH;
    }

    public function storeHashTags($post, $hashTagIds, $userId)
    {
        $post->hashTags()->attach($hashTagIds, [
            'created_by' => $userId,
        ]);

        foreach ($post->hashTags as $hashTag) {
            $hashTag->increment('point', PointEnum::POINT_HASH_TAG);
        }
    }

    public function update($data, $images, $videos, $post, $user, $imageTags)
    {
        $postImageIds = $post->images->pluck('id')->toArray();
        $imageTags = array_column($imageTags, 'tags', 'image_name');
        $imageTagsStore = [];

        foreach ($imageTags as $key => $value) {
            if (in_array($key, $postImageIds)) {
                $this->updateImageTags($key, $imageTags[$key]);
            } else {
                $imageTagsStore[$key] = $imageTags[$key];
            }
        }

        DB::beginTransaction();

        try {
            $oldMediaType = $post->media_type;
            $this->updateBasic($data, $post, $user);

            if (isset($data['hash_tag_ids'])) {
                $this->updateHashTags($post, $data['hash_tag_ids']);
            }

            if (isset($data['deleted_s3_images'])) {
                $this->deleteImages($data['deleted_s3_images'], $user->id);
            }

            if (isset($data['deleted_s3_videos'])) {
                $this->deleteVideos($data['deleted_s3_videos'], $user->id);
            }

            if (!is_null($images)) {
                $this->storeFiles($images, PostEnum::MEDIA_TYPE_IMAGE, $post, $user, $imageTagsStore);
                if ($oldMediaType == PostEnum::MEDIA_TYPE_VIDEO) {
                    $post->videos()->delete();
                }
            }

            if (!is_null($videos)) {
                $this->storeFiles($videos, PostEnum::MEDIA_TYPE_VIDEO, $post, $user);
                if ($oldMediaType == PostEnum::MEDIA_TYPE_IMAGE) {
                    $post->images()->delete();
                }
            }

            if (!empty($data['time_public'])) {
                $this->updateDraftPost($post->draftPost, $data['time_public']);
            }

            DB::commit();

            return $this->postRepository->findById($post->id);
        } catch (\Exception $e) {
            DB::rollback();

            report($e);

            return trans('post.updated_failed');
        }
    }

    public function updateBasic($data, $post, $user)
    {
        return $this->postRepository->update($post, [
            'title' => $data['title'] ?? null,
            'content' => $data['content'] ?? null,
            'location_name' => $data['location_name'] ?? null,
            'location_lat' => $data['location_lat'] ?? null,
            'location_long' => $data['location_long'] ?? null,
            'thumbnail' => $data['thumbnail'] ?? null,
            'thumbnail_width' => $data['thumbnail_width'] ?? null,
            'thumbnail_height' => $data['thumbnail_height'] ?? null,
            'media_type' => $data['media_type'] ?? null,
            'account_type' => $user->roles()->pluck('id')->first() ?? null,
            'status' => $data['status'] ?? null,
            'updated_by' => $user->id,
            'action_time' => date('Y-m-d H:i:s'),
            'current_language' => $this->detectLanguage($data['content'])
        ]);
    }

    public function updateHashTags($post, $hashTagIds)
    {
        return $post->hashTags()->sync($hashTagIds);
    }

    public function updateImageTags($imageId, $data)
    {
        $imageTags = $this->imageRepository->findById($imageId)->imageTags();
        $imageTags->delete();
        $imageTags->createMany($data);
    }

    public function deleteImages($data, $userId)
    {
        $this->uploadService->deleteS3Files($data);
        Log::info("Deleted images in S3 successfully!");

        $query = $this->imageRepository->whereImagePath($data);
        $images = $query->get();
        foreach ($images as $image) {
            $image->imageTags()->delete();
        }
        $query->update(['deleted_by' => $userId]);
        $query->delete();

        Log::info("Deleted images in local successfully!");
    }

    public function deleteVideos($data, $userId)
    {
        $this->uploadService->deleteS3Files($data);
        Log::info("Deleted videos in S3 successfully!");

        $this->videoRepository->whereVideoPath($data)->update(['deleted_by' => $userId]);
        $this->videoRepository->whereVideoPath($data)->delete();
        Log::info("Deleted videos in local successfully!");
    }

    public function storeDraftPost($post, $timePublic)
    {
        $post->draftPost()->create([
            'post_id' => $post->id,
            'time_public' => $timePublic,
        ]);
    }

    public function updateDraftPost($draftPost, $timePublic)
    {
        $draftPost->update([
            'time_public' => $timePublic,
        ]);
    }

    public function publicPost()
    {
        $query = Post::whereHas('draftPost', function ($q) {
            // Date format should be 'Y-m-d H:i' because the job is called every MINUTE (not every SECOND).
            $q->where('time_public', Carbon::now()->format('Y-m-d H:i'));
        });

        if ($query->exists()) {
            $this->publishDraftPosts($query);

            $this->pushNotificationAfterPublishing($query);
        }
    }

    public function publishDraftPosts($query)
    {
        $count = $query->update([
            'status' => PostEnum::STATUS_ACTIVE,
            'action_time' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        Log::info("Published $count draft post(s) SUCCESSFULLY!");
    }

    public function pushNotificationAfterPublishing($query)
    {
        $posts = $query->with('user')->get();
        $devices = [];

        foreach ($posts->chunk(config('api.post.chunk_post_for_pushing_notification')) as $chunk) {
            foreach ($chunk as $post) {
                $devices = array_merge($devices, $post->user->tokenDevices->pluck('uuid')->toArray());
                $devices = array_unique($devices);
                $title = trans('notification.public_post_title');
                $body = trans('notification.public_post_body', [
                    'post_title' => $post->title ?? null,
                ]);

                // Push notification at here
                $data = [
                    'topicName' => 'User',
                    'title' => $title,
                    'body' => $body
                ];

                $this->notificationService->makeQueueJobNotification($devices, $data);

                Log::info('Pushed notification SUCCESSFULLY!');
            }
        }
    }

    /**
     * Get posts for user
     *
     * @param $userId
     *
     * @return builder
     */
    public function getPostsByUserId($userId)
    {
        return $this->postRepository->getPostsUserIds([$userId])->latest();
    }

    /**
     * Get posts liked for user
     *
     * @param $user
     *
     * @return [builder]
     */
    public function getPostsLikedByUser($user)
    {
        $postLikedIds = $user->likes()->whereLikeableType(LikeEnum::MODEL_POST)
            ->whereIsLiked(LikeEnum::STATUS_LIKE)
            ->pluck('likeable_id')
            ->toArray();

        return $this->postRepository->getPostsLikedByUserId($postLikedIds);
    }

    /**
     * Get posts liked for user
     *
     * @param $user
     * @param $collectionId
     *
     * @return [builder]
     */
    public function getPostsBookmarkedByUser($user, $collectionId)
    {
        if (empty($collectionId)) {
            $collectionId = $user->collections()
                ->whereName(CollectionEnum::NAME_DEFAULT)
                ->whereType(CollectionEnum::BOOKMARK)
                ->value('id');
        }
        $postsBookmarkedIds = $user->favorites()
            ->whereCollectionId($collectionId)
            ->whereIsFavorited(FavoriteEnum::IS_FAVORITE)
            ->pluck('post_id')
            ->toArray();

        return $this->postRepository->getPostsBookmarkedByUser($postsBookmarkedIds);
    }

    /**
     * Share Post
     *
     * @param $post
     * @param $type
     *
     * @return boolean
     */
    public function share($post, $type)
    {
        return $this->postRepository->share($post, $type);
    }

    /**
     * @return collection
     */
    public function getReasonReport()
    {
        return $this->labelReasonRepository->getByType(ReportEnum::REPORT_POST);
    }

    /**
     * Action report
     *
     * @param $post
     * @param $reasonId
     *
     * @return boolean
     */
    public function actionReport($post, $reasonId)
    {
        $post->reports()->create([
            'reportable_type' => ReportEnum::MODEL_POST,
            'user_id' => Auth::id(),
            'reason' => $reasonId,
            'status' => ReportEnum::STATUS_PENDING
        ]);

        return $post->id;
    }

    /**
     * Action hidden
     *
     * @param $post
     *
     * @return boolean
     */
    public function hidden($post)
    {
        $post->hiddens()->firstOrCreate([
            'user_id' => Auth::id(),
            'hiddenable_type' => HiddenEnum::MODEL_POST
        ]);

        return $post->id;
    }

    public function destroy($post)
    {
        DB::beginTransaction();
        try {
            $oldId = $post->id;
            if (!empty($post->images->toArray())) {
                $this->uploadService->deleteS3Files($post->images->pluck('image_path')->toArray());
            }
            if (!empty($post->videos->toArray())) {
                $this->uploadService->deleteS3Files($post->videos->pluck('video_path')->toArray());
            }

            $post->delete();
            $post->shares()->delete();
            $post->bookmarks()->delete();
            $post->videos()->delete();
            $post->images()->delete();
            $post->comments()->delete();
            $post->likes()->delete();
            $post->reports()->delete();
            $post->lastActions()->delete();
            $post->hiddens()->delete();

            DB::commit();

            return $oldId;
        } catch (\Exception $exception) {
            DB::rollBack();

            return false;
        }
    }

    public function getDraft()
    {
        return Auth::user()->posts()->whereStatus(PostEnum::STATUS_DRAFT);
    }

    // CMS service

    public function getPostListCms()
    {
        return $this->postRepository->getListPostCms();
    }

    public function getAccountForSearch()
    {
        return $this->userRepository->getAccountForSearch();
    }

    public function searchPosts($request)
    {
        return $this->postRepository->searchPosts($request);
    }

    public function showPostsCms($slug)
    {
        return $this->postRepository->showPostsCms($slug);
    }

    public function getListPostWithAccountCms($accountId)
    {
        return $this->postRepository->getListPostWithAccountCms($accountId);
    }
}
