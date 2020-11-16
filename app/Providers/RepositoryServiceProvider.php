<?php

namespace App\Providers;

use App\Models\Like;
use App\Models\Page;
use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use App\Models\Image;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Follow;
use App\Models\Comment;
use App\Models\HashTag;
use App\Models\Bookmark;
use App\Models\Interest;
use App\Models\Collection;
use App\Models\LabelReason;
use App\Models\Notification;
use App\Models\SearchHistory;
use App\Models\BusinessCategory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Like\LikeRepository;
use App\Repositories\Page\PageRepository;
use App\Repositories\Post\PostRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Share\ShareRepository;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Topic\TopicRepository;
use App\Repositories\Video\VideoRepository;
use App\Repositories\Follow\FollowRepository;
use App\Repositories\Search\SearchRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Bookmark\BookmarkRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Interest\InterestRepository;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Page\PageRepositoryInterface;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\Reason\LabelReasonRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Share\ShareRepositoryInterface;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Repositories\Topic\TopicRepositoryInterface;
use App\Repositories\Video\VideoRepositoryInterface;
use App\Repositories\Collection\CollectionRepository;
use App\Repositories\Follow\FollowRepositoryInterface;
use App\Repositories\Search\SearchRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Interest\InterestRepositoryInterface;
use App\Repositories\Bookmark\BookmarkRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Repositories\Reason\LabelReasonRepositoryInterface;
use App\Repositories\Collection\CollectionRepositoryInterface;
use App\Repositories\BusinessCategory\BusinessCategoryRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\BusinessCategory\BusinessCategoryRepositoryInterface;

/**
 * RepositoryServiceProvider
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function register()
    {
        $db = $this->app->make(DatabaseManager::class);

        $this->app->singleton(UserRepositoryInterface::class, function () use ($db) {
            return new UserRepository(new User(), $db);
        });

        $this->app->singleton(TopicRepositoryInterface::class, function () use ($db) {
            return new TopicRepository(new HashTag(), $db);
        });

        $this->app->singleton(PostRepositoryInterface::class, function () use ($db) {
            return new PostRepository(new Post(), $db, new UserRepository(new User(), $db));
        });

        $this->app->singleton(CommentRepositoryInterface::class, function () use ($db) {
            return new CommentRepository(new Comment(), $db);
        });

        $this->app->singleton(PageRepositoryInterface::class, function () use ($db) {
            return new PageRepository(new Page(), $db);
        });

        $this->app->singleton(LikeRepositoryInterface::class, function () use ($db) {
            return new LikeRepository(new Like(), $db);
        });

        $this->app->singleton(FollowRepositoryInterface::class, function () use ($db) {
            return new FollowRepository(new Follow(), $db);
        });

        $this->app->singleton(CategoryRepositoryInterface::class, function () use ($db) {
            return new CategoryRepository(new Topic(), $db);
        });

        $this->app->singleton(CollectionRepositoryInterface::class, function () use ($db) {
            return new CollectionRepository(new Collection(), $db);
        });

        $this->app->singleton(FavoriteRepositoryInterface::class, function () use ($db) {
            return new FavoriteRepository(new Topic(), $db);
        });

        $this->app->singleton(ImageRepositoryInterface::class, function () use ($db) {
            return new ImageRepository(new Image(), $db);
        });

        $this->app->singleton(VideoRepositoryInterface::class, function () use ($db) {
            return new VideoRepository(new Video(), $db);
        });

        $this->app->singleton(BookmarkRepositoryInterface::class, function () use ($db) {
            return new BookmarkRepository(new Bookmark(), $db);
        });

        $this->app->singleton(SearchRepositoryInterface::class, function () use ($db) {
            return new SearchRepository(new SearchHistory(), $db);
        });

        $this->app->singleton(NotificationRepositoryInterface::class, function () use ($db) {
            return new NotificationRepository(new Notification(), $db);
        });

        $this->app->singleton(LabelReasonRepositoryInterface::class, function () use ($db) {
            return new LabelReasonRepository(new LabelReason(), $db);
        });

        $this->app->singleton(BusinessCategoryRepositoryInterface::class, function () use ($db) {
            return new BusinessCategoryRepository(new BusinessCategory(), $db);
        });

        $this->app->singleton(InterestRepositoryInterface::class, function () use ($db) {
            return new InterestRepository(new Interest(), $db);
        });

        $this->app->singleton(ShareRepositoryInterface::class, function () use ($db) {
            return new ShareRepository(new Share(), $db);
        });
    }
}
