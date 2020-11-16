<template>
<section class="content" v-if="postsSearch">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $t('posts.search') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form role="form" v-on:keydown.enter.prevent="searchPosts()">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- select -->
                                <div class="form-group">
                                    <label>{{ $t('posts.accounts') }}</label>
                                    <multiselect v-model="listAccounts" :tag-placeholder="$t('posts.search_this_new_a_account')" :placeholder="$t('posts.search_this_new_a_account')" label="name" track-by="code" :options="accounts" :multiple="true" :taggable="true"></multiselect>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <!-- select -->
                                <div class="form-group">
                                    <label>{{ $t('posts.start_date') }}</label>
                                    <input type="date" v-model="search.start_date" v-bind:class="{'is-invalid': errors && objectNotEmpty(errors.start_date)}" class="form-control">
                                    <span v-if="errors && objectNotEmpty(errors.start_date)" class="error invalid-feedback">{{ errors.start_date[0] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <!-- select -->
                                <div class="form-group">
                                    <label>{{ $t('posts.end_date') }}</label>
                                    <input type="date" v-model="search.end_date" v-bind:class="{'is-invalid': errors && objectNotEmpty(errors.end_date)}"  class="form-control">
                                    <span v-if="errors && objectNotEmpty(errors.end_date)" class="error invalid-feedback">{{ errors.end_date[0] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <!-- select -->
                                <div class="form-group">
                                    <label>{{ $t('posts.status') }}</label>
                                    <select class="form-control" v-model="search.status">
                                        <option value="">{{ $t('posts.please_choose_status') }}</option>
                                        <option value="1">{{ $t('posts.shared') }}</option>
                                        <option value="3">{{ $t('posts.draft') }}</option>
                                        <option value="2">{{ $t('posts.pending') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('posts.title') }}</label>
                                    <textarea class="form-control" v-model.trim="search.title" rows="3" :placeholder="$t('posts.title')"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('posts.content') }}</label>
                                    <textarea class="form-control" v-model.trim="search.content" rows="3" :placeholder="$t('posts.content')"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" @click="searchPosts()" class="btn btn-primary float-right">
                                <i class="fas fa-search"></i>
                                {{ $t('posts.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget" v-for="(post, index) in postsSearch.data" :key="index">
                <div class="card-body">
                    <div class="attachment-block clearfix col-md-12">
                        <div class="col-md-3">
                            <router-link :to="{ name: 'Detail', params: { slug: post.slug }}">
                                <img style="max-height: 300px; max-width: 150px; margin-right: 10px;" class="attachment-img" :src="post.thumbnail" alt="Attachment Image"/>
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
                                {{ $t('account_profile.time_publish') }}: {{ post.draft_post ? post.draft_post.time_public : null}}
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
                    <span class="float-left text-muted">{{ post.total_likes }} <i class="fas fa-thumbs-up"></i> - {{ post.total_comments }} <i class="fas fa-comments"></i> - {{ post.total_views }} <i class="fas fa-eye"></i> - {{ post.total_bookmarks }} <i class="far fa-bookmark"></i> - {{ post.total_shares }} <i class="fas fa-share"></i></span>

                    <span class="float-right text-muted">
                        <img class="img-circle img-bordered-sm" style="width:40px; height: 40px;" :src="post.users.avatar ? post.users.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" alt="User Image">
                        <a href="#"><i v-if="post.users.is_business_account" class="fas fa-crown"></i> {{ post.users.name }} </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-5">
            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">{{ $t('posts.showing') }} {{ postsSearch.meta.from }} {{ $t('posts.to') }} {{ postsSearch.meta.to }} {{ $t('posts.of') }} {{ postsSearch.meta.total }} {{ $t('posts.entries') }}</div>
        </div>
        <div class="col-sm-12 col-md-7">
            <pagination :data="postsSearch" @pagination-change-page="searchPosts($event)" class="float-right">
            </pagination>
        </div>
    </div>
    <!-- /.row -->
</section>
</template>

<script>
import {
    mapState
} from 'vuex';

import Pagination from 'laravel-vue-pagination';
import Multiselect from 'vue-multiselect';
import Helper from '@/library/hepler';

export default {
    name: 'PostList',
    components: {
        Pagination,
        Multiselect
    },
    data() {
        return {
            query: {
                page: 1,
            },
            search: {
                account_id: '',
                start_date: null,
                end_date: null,
                status: '',
                title: null,
                content: null
            },
            listAccounts: null
        };
    },

    watch: {
        'search.start_date'() {
            if (this.errors) {
                this.errors.start_date = null;
            }
        },
        'search.end_date'() {
            if (this.errors) {
                this.errors.end_date = null;
            }
        },
    },

    computed: {
        ...mapState({
            accounts: state => state.storePosts.accounts,
            errors: state => state.storePosts.errors,
            postsSearch: state => state.storePosts.postsSearch
        }),
    },

    filters: {
        formatDescription(text, length, clamp) {
            if (text && length) {
                return Helper.truncate(text, length, clamp);
            }
        },
    },

    created() {
        this.$store.commit('SET_ACTIVE_ASIDE', {
            aside: 'posts'
        });
        this.getListAccount();
        this.searchPosts();
    },

    methods: {
        async getListAccount() {
            await this.$store.dispatch('actionGetAccount', {
                vue: this,
            });
        },

        async searchPosts(page) {
            let query = this.query;
            if (page) {
                query.page = page;
            }

            if (this.search.start_date === '') {
                this.search.start_date = null;
            }

            if (this.search.end_date === '') {
                this.search.end_date = null;
            }

            if (this.listAccounts) {
                let array = [];
                this.listAccounts.forEach(function(item) {
                    array.push(item.code);
                });
                this.search.account_id = array;
            }

            await this.$store.dispatch('actionSearchPosts', {
                vue: this,
                params: this.search,
                page: query
            });
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        }
    }
};
</script>
