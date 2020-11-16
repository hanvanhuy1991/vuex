<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'domain' => env('DOMAIN'),
    'middleware' => 'apiDebugbar',
    'prefix' => 'v1'
], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('check-email', 'AuthController@checkEmail');
    Route::post('register', 'AuthController@signup');
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::get('favorites', 'FavoriteController@index');
    Route::resource('pages', 'PageController')->only(['index']);
    Route::get('test/notifications', 'NotificationController@testNotification'); // Test notifications

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        /**
         * Auth
         */
        Route::get('logout', 'AuthController@logout');

        /**
         * Users
         */
        Route::post('change-password', 'UserController@changePassword');
        Route::resource('users', 'UserController')->only([
            'show',
            'update',
        ]);
        Route::get('users/{user}/followings', 'UserController@getFollowing');
        Route::get('users/{user}/followers', 'UserController@getFollower');
        Route::get('my-profiles', 'UserController@getMyProfile');
        Route::post('phone-contacts', 'UserController@updatePhoneContact');
        Route::post('locations', 'UserController@updateLocation');
        Route::get('suggestions', 'UserController@suggestion');

        /**
         * Posts
         */
        Route::resource('posts', 'PostController');
        Route::group([
            'prefix' => 'users/{user}/posts'
        ], function () {
            Route::get('', 'PostController@getPostsByUserId');
            Route::get('/liked', 'PostController@getPostsLikedByUser');
            Route::get('/bookmarked/{collectionId?}', 'PostController@getPostsBookmarkedByUser');
        });

        /**
         * Comments
         */
        Route::get('posts/{post}/comments', 'CommentController@getCommentForPost');
        Route::post('posts/{post}/comments', 'CommentController@store');
        Route::resource('comments', 'CommentController')->only(['update', 'destroy']);

        /**
         * Likes
         */
        Route::post('posts/{post}/likes', 'LikeController@likePost');
        Route::post('comments/{comment}/likes', 'LikeController@likeComment');

        /**
         * Follows
         */
        Route::post('users/{user}/follows', 'FollowController@followUser');
        Route::post('users/{user}/remove', 'FollowController@removeFollower');

        /**
         * Categories
         */
        Route::resource('categories', 'CategoryController');

         /**
         * Topics
         */
        Route::resource('topics', 'TopicController');
        Route::get('search/topics', 'TopicController@search');
        Route::get('categories/{categoryId}/topics', 'TopicController@searchTopicWithCategory');
        
        /**
         * Collections
         */
        Route::resource('collections', 'CollectionController')->only(['index', 'store', 'show']);

        /**
         * Bookmarks
         */
        Route::post('posts/{post}/bookmarks', 'BookmarkController@store');
        Route::post('posts/{post}/move-bookmarks', 'BookmarkController@move');

        /**
         * Notifications
         */
        Route::resource('notifications', 'NotificationController');
        Route::get('count/notifications', 'NotificationController@countNotifications');
        Route::get('notification-settings', 'NotificationController@getSetting');
        Route::post('notification-settings', 'NotificationController@postSetting');

        /**
         * Search
         */
        Route::get('search/histories', 'SearchController@histories');
        Route::delete('search/destroy', 'SearchController@clearHistory');
        Route::get('search/categories', 'CategoryController@search');
        Route::group([
            'prefix' => 'search/results'
        ], function () {
            Route::get('topics', 'SearchController@getTopic');
            Route::get('people', 'SearchController@getPeople');
            Route::get('users', 'SearchController@getUser');
            Route::get('suggests', 'SearchController@getSuggest');
            Route::get('posts', 'SearchController@getPost');
            Route::get('brands', 'SearchController@getBrand');
        });

        /**
         * Shares
         */
        Route::post('posts/{post}/shares', 'PostController@share');

        /**
         * Reports
         */
        Route::get('users/{user}/reports', 'UserController@getReasonReport');
        Route::post('users/{user}/reports', 'UserController@actionReport');

        Route::get('posts/{post}/reports', 'PostController@getReasonReport');
        Route::post('posts/{post}/reports', 'PostController@actionReport');

        Route::get('comments/{comment}/reports', 'CommentController@getReasonReport');
        Route::post('comments/{comment}/reports', 'CommentController@actionReport');

        /**
         * Hidden
         */
        Route::post('posts/{post}/hidden', 'PostController@hidden');

        /**
         * Blocks
         */
        Route::get('blocks', 'BlockController@index');
        Route::post('users/{user}/blocks', 'BlockController@store');

        /**
         * Unis
         */
        Route::get('my-unis/all', 'CollectionController@uniAll');
        Route::get('my-unis/book', 'CollectionController@uniBook');

        /**
         * Helps
         */
        Route::resource('helps', 'HelpController')->only(['store']);

        /**
         * Draft
         */
        Route::get('drafts', 'PostController@getDraft');

        /**
         * Data Sta
         */
        Route::get('statistics', 'StatisticController@getDraft');
        Route::group([
            'prefix' => 'statistics'
        ], function () {
            Route::group([
                'prefix' => 'followers'
            ], function () {
                Route::get('total', 'StatisticController@getTotalFollower');
                Route::get('ages', 'StatisticController@getAgeFollower');
                Route::get('genders', 'StatisticController@getGenderFollower');
                Route::get('follower-charts', 'StatisticController@getFollowerChart');
            });
            Route::get('engagements', 'StatisticController@engagement');
        });

        /**
         * Translate
         */
        Route::get('posts/{post}/translates/{language?}', 'TranslateController@postTranslate');
    });
});



Route::group([
    'domain' => env('SUB_DOMAIN'),
    'namespace' => 'Admin',
    'prefix' => 'v1',
    'middleware' => 'apiDebugbar'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('forgot-password', 'AuthController@forgotPassword');
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            // Authentication
            Route::get('logout', 'AuthController@logout');
            Route::get('profile', 'AuthController@profile');
            Route::put('profile/{id}', 'AuthController@updateProfile');
            Route::post('change-password', 'AuthController@changePassword');

            // Post management
            Route::resource('posts', 'PostController');
            Route::get('search-accounts', 'PostController@getAccountForSearch');
            Route::get('search-posts', 'PostController@searchPosts');
            Route::get('posts/{post}/comments', 'CommentController@getCommentForPostCms');

            // Account Management
            Route::resource('accounts', 'AccountController');
            Route::get('/accounts/{account}/posts', 'AccountController@getListPosts');
            Route::get('list-business', 'BusinessCategoryController@getListBusinessCategory');
        });
    });
