<template>
<section class="content">
    <div class="row" v-if="account">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" :src=" account.avatar ? account.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ account.name }}</h3>

                    <p class="text-muted text-center" v-for="(role, index) in account.role" :key="index"><span class="badge badge-success">{{ role.name }}</span></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.point') }}</b> <a class="float-right"> <span class="badge badge-secondary"> {{ account.point ? account.point : 0 }}</span></a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.status') }}</b>
                            <a class="float-right">
                                <span :class="[account.status == 1 ? 'badge-success' : account.status == 2 ? 'badge-danger' : 'badge-warning']" class="badge">{{ account.status == 1 ? $t('account.active') : account.status == 2 ? $t('account.locked') : $t('account.temporary_locked') }}</span>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.created_date') }}</b> <a class="float-right">{{ account.created_at }}</a>
                        </li>
                        <li class="list-group-item" v-if="account.isBusinessAccount">
                            <b style="color:red">{{ $t('account_profile.expiry_date') }}</b> <a style="color:red" class="float-right">{{ account.expiry_date }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.date_of_birth') }}</b> <a class="float-right">{{ account.birthday }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.age') }}</b> <a class="float-right"><span class="badge badge-secondary"> {{ account.age }} </span></a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.email') }}</b> <a class="float-right">{{ account.email }}</a>
                        </li>
                        <li class="list-group-item" v-if="account.isBusinessAccount">
                            <b>{{ $t('account_profile.phone') }}</b> <a class="float-right">{{ account.phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.followers') }}</b> <a class="float-right"><span class="badge badge-secondary"> {{ account.total_followers }}</span></a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.following') }}</b> <a class="float-right"><span class="badge badge-secondary">{{ account.total_following }}</span></a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ $t('account_profile.bookmarks') }}</b> <a class="float-right"><span class="badge badge-secondary">{{ account.total_bookmarks }}</span></a>
                        </li>

                        <li class="list-group-item" v-if="account.isBusinessAccount">
                            <b>{{ $t('account_profile.address') }}</b> <a class="float-right">{{ account.address }}</a>
                        </li>
                        <li class="list-group-item" v-if="account.isBusinessAccount">
                            <b>{{ $t('account_profile.website') }}</b> <a class="float-right">{{ account.website }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $t('account_profile.about') }}</h3>
                </div>
                <div class="card-body">
                    <strong v-if="account.isBusinessAccount"><i class="fas fa-info"></i> {{ $t('account_profile.description') }}</strong>
                    <p v-if="account.isBusinessAccount" class="text-muted">{{ account.description }}</p>
                    <hr v-if="account.isBusinessAccount">

                    <strong v-if="account.isBusinessAccount"><i class="fas fa-pencil-alt mr-1"></i> {{ $t('account_profile.category') }}</strong>

                    <p class="text-muted" v-if="account.isBusinessAccount">
                        <span class="badge badge-secondary" v-for="(category, index) in account.categories" :key="index">{{ category.name }}</span>
                    </p>

                    <hr>

                    <strong><i class="far fa-file-alt mr-1"></i> {{ $t('account_profile.interested') }}</strong>

                    <p class="text-muted">
                        <span class="badge badge-secondary" v-for="(interest, index) in account.interestes" :key="index">{{ interest.name }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#posts" data-toggle="tab">{{ $t('account_profile.posts') }} <span v-if="posts" class="badge badge-light">{{ posts.meta.total }}</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#followings" data-toggle="tab">{{ $t('account_profile.following') }} <span class="badge badge-light">10</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#list-followers" data-toggle="tab">{{ $t('account_profile.followers') }} <span class="badge badge-light">20</span> </a></li>
                        <li class="nav-item"><a class="nav-link" href="#favorites" data-toggle="tab">{{ $t('account_profile.favorites') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#blocks" data-toggle="tab">{{ $t('account_profile.blocks') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#consider" data-toggle="tab">{{ $t('account_profile.consider') }}</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body" style="height: 49.4rem; overflow: auto;">
                    <div class="tab-content">
                        <div class="tab-pane active" id="posts" v-if="posts">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-widget" v-for="(post, index) in list" :key="index">
                                        <div class="card-body">
                                            <div class="attachment-block clearfix col-md-12">
                                                <div class="col-md-3">
                                                    <router-link :to="{ name: 'Detail', params: { slug: post.slug }}">
                                                        <img class="attachment-img" :src="post.thumbnail" alt="Attachment Image" style="max-height: 300px; max-width: 150px; margin-right: 10px;" />
                                                    </router-link>
                                                </div>

                                                <div class="attachment-pushed col-md-9">
                                                    <h4 class="attachment-heading">
                                                        <router-link :to="{ name: 'Detail', params: { slug: post.slug }}">{{ post.title }}</router-link>
                                                    </h4>
                                                    <div class="attachment-text">
                                                        {{ post.content | formatDescription(100, '...') }}
                                                    </div>
                                                    <div class="attachment-text">
                                                        {{ $t('posts.status') }}: <span :class="[post.status == 1 ? 'badge-success' : post.status == 2 ? 'badge-warning' :  'badge-primary']" class="badge"> {{ post.status == 1 ? $t('posts.shared') : post.status == 2 ? $t('posts.pending') :  $t('posts.draft')}} </span>
                                                    </div>
                                                    <div class="attachment-text" v-if="post.status == 2">
                                                        {{ $t('account_profile.time_publish') }}: {{ post.draft_post.time_public }}
                                                    </div>
                                                    <div class="attachment-text">
                                                        {{ $t('posts.location') }}: <span class="badge badge-secondary"><i class="fas fa-map-marker-alt"></i> {{ post.location_name }}</span>
                                                    </div>
                                                    <div class="attachment-text">
                                                        {{ $t('posts.topic') }}: <span class="badge badge-secondary" v-for="(tag, index) in post.hash_tags" :key="index"><i class="fas fa-tag"></i> {{ tag.title }}</span>
                                                    </div>
                                                    <div class="attachment-text">
                                                        {{ $t('posts.created_at') }}: <span class="badge badge-secondary">{{ post.created_at }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="float-left text-muted">{{ post.total_likes }} <i class="fas fa-thumbs-up"></i> {{ post.total_comments }} <i class="fas fa-comments"></i> {{ post.total_views }} <i class="fas fa-eye"></i> {{ post.total_bookmarks }} <i class="far fa-bookmark"></i> {{ post.total_shares }} <i class="fas fa-share"></i></span>

                                            <span class="float-right text-muted">
                                                <img class="img-circle img-bordered-sm" style="width:40px; height: 40px;" :src="post.users.avatar ? post.users.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" alt="User Image">
                                                <a href="#"><i v-if="post.users.is_business_account" class="fas fa-crown"></i> {{ post.users.name }} </a>
                                            </span>
                                        </div>
                                    </div>
                                    <infinite-loading @infinite="showListPosts">
                                        <span slot="no-more">
                                            {{ $t('common.no_more_data') }}
                                        </span>
                                    </infinite-loading>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="followings">
                            <div class="col-md-12 col-sm-6">
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-business" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Business Account <span class="badge badge-light">5</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-unicer" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Unicer <span class="badge badge-light">5</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade active show" id="custom-tabs-four-business" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                <div class="container">
                                                    <div class="row bootstrap snippets bootdey">
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="panel">
                                                                <div class="panel-heading">
                                                                    <div class="form-group row">
                                                                        <label for="inputName" class="col-sm-2 col-form-label">Search</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="email" class="form-control" id="inputName" placeholder="Name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>

                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>

                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-unicer" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <div class="form-group row">
                                                                <label for="inputName" class="col-sm-2 col-form-label">Search</label>
                                                                <div class="col-sm-10">
                                                                    <input type="email" class="form-control" id="inputName" placeholder="Name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="list-followers">
                            <div class="col-md-12 col-sm-6">
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-followers-business" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Business Account <span class="badge badge-light">10</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-followers-unicer" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Unicer <span class="badge badge-light">10</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade active show" id="custom-tabs-followers-business" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                <div class="container">
                                                    <div class="row bootstrap snippets bootdey">
                                                        <div class="col-md-12 col-xs-12">
                                                            <div class="panel">
                                                                <div class="panel-heading">
                                                                    <div class="form-group row">
                                                                        <label for="inputName" class="col-sm-2 col-form-label">Search</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="email" class="form-control" id="inputName" placeholder="Name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>

                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>

                                                                    <ul class="list-group list-group-dividered list-group-full">
                                                                        <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                            <div class="media">
                                                                                <div class="media-left">
                                                                                    <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                        <i></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="float-right">
                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                                    </div>
                                                                                    <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                                    <small>@heavybutterfly920</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-followers-unicer" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <div class="form-group row">
                                                                <label for="inputName" class="col-sm-2 col-form-label">Search</label>
                                                                <div class="col-sm-10">
                                                                    <input type="email" class="form-control" id="inputName" placeholder="Name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <ul class="list-group list-group-dividered list-group-full">
                                                                <li class="list-group-item" style="border: unset; border-bottom: 1px solid rgba(0,0,0,.125);">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <a class="avatar avatar-online" href="javascript:void(0)">
                                                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                                <i></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <div class="float-right">
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light"><i class="fas fa-eye"></i> Views</button>
                                                                            </div>
                                                                            <div><a class="name" href="javascript:void(0)">Willard Wood</a></div>
                                                                            <small>@heavybutterfly920</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="favorites">
                            This is list Favorites
                        </div>

                        <div class="tab-pane" id="blocks">
                            This is list Blocks
                        </div>

                        <div class="tab-pane" id="consider">
                            This is list Consider
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </div>
    </div>
</section>
</template>

<script>
import {
    mapState
} from 'vuex';
import Helper from '@/library/hepler';
import InfiniteLoading from 'vue-infinite-loading';

export default {
    name: 'Profile',
    components: {
        InfiniteLoading
    },

    data() {
        return {
            query: {
                pagePosts: 1,
            },
            id: this.$route.params.id,
            list: []
        };
    },

    created() {
        this.$store.commit('SET_ACTIVE_ASIDE', {
            aside: 'users'
        });

        this.showDetailAccount();
        this.showListPosts();
    },

    computed: {
        ...mapState({
            account: state => state.storeAccounts.account,
            posts: state => state.storeAccounts.posts
        }),
    },

    filters: {
        formatDescription(text, length, clamp) {
            if (text && length) {
                return Helper.truncate(text, length, clamp);
            }
        },
    },

    watch: {

    },

    methods: {
        async showDetailAccount() {
            await this.$store.dispatch('actionShowDetailAccount', {
                vue: this,
                id: this.id
            });
        },

        async showListPosts($state) {
            await this.$store.dispatch('actionShowListPosts', {
                vue: this,
                id: this.id,
                page: this.query.pagePosts
            });

            if (this.posts.data.length) {
                const temp = [];
                this.posts.data.forEach(function (item) {
                    temp.push(item);
                });
                this.list = this.list.concat(temp);
                this.query.pagePosts = this.query.pagePosts + 1;
                if ($state) {
                    $state.loaded();
                }
            } else {
                $state.complete();
            }
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        }
    }
};
</script>

<style lang="css">
.avatar {
    position: relative;
    display: inline-block;
    width: 40px;
    white-space: nowrap;
    border-radius: 1000px;
    vertical-align: bottom
}

.avatar i {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 10px;
    height: 10px;
    border: 2px solid #fff;
    border-radius: 100%
}

.avatar img {
    width: 100%;
    max-width: 100%;
    height: auto;
    border: 0 none;
    border-radius: 1000px
}
</style>
