<template>
    <section class="content">
        <div class="row" v-if="post">
            <div class="col-md-7">
                <div class="card card-widget">
                    <div class="card-header">
                        <div class="user-block">
                            <img class="img-circle" :src="post.users.avatar ? post.users.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" alt="User Image">
                            <span class="username">
                                    <a href="/">
                                        <i v-if="post.users.is_business_account" class="fas fa-crown"></i> {{ post.users.name }}
                                    </a>
                                </span>
                            <span class="description">{{ $t('posts.created_at') }}: {{ post.created_at }}</span>
                        </div>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <carousel-3d v-if="post.media_type == 'image'" :controls-visible="true" :clickable="true">
                            <slide v-for="(image, i) in post.images" :index="i" :key="i">
                                <viewer :options="options">
                                    <img :src="image.image_path">
                                </viewer>
                            </slide>
                        </carousel-3d>
                        <div v-if="post.media_type == 'video'" style="with:100%; height: 100%">
                            <div v-for="(video, i) in post.videos" :index="i" :key="i">
                                <Media :kind="'video'" :isMuted="(false)" :src="video.video_path" :autoplay="false" :controls="true" :loop="true" :ref="'fish'" :style="{width: '100%'}">
                                </Media>
                            </div>
                        </div>
                        <span class="float-right text-muted">{{ post.total_likes }} <i class="fas fa-thumbs-up"></i> - {{ post.total_comments }} <i class="fas fa-comments"></i> - {{ post.total_views }} <i class="fas fa-eye"></i> - {{ post.total_bookmarks }} <i class="far fa-bookmark"></i> - {{ post.total_shares }} <i class="fas fa-share"></i></span>
                        <div class="clearfix"></div>
                        <div class="attachment-text">
                            {{ $t('posts.status') }}: <span :class="[post.status == 1 ? 'badge-success' : post.status == 2 ? 'badge-warning' :  'badge-primary']" class="badge"> {{ post.status == 1 ? $t('posts.shared') : post.status == 2 ? $t('posts.pending') :  $t('posts.draft')}} </span>
                        </div>
                        <div class="attachment-text">
                            {{ $t('posts.location') }}: <span class="badge badge-secondary"><i class="fas fa-map-marker-alt"></i> {{ post.location_name }}</span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="attachment-text">
                            {{ $t('posts.topic') }}: <span class="badge badge-secondary" v-for="(tag, index) in post.hash_tags" :key="index"><i class="fas fa-tag"></i> {{ tag.title }}</span>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <h4>{{ post.title }}</h4>
                    </div>
                    <div class="card-footer card-comments">
                        <div class="card-comment">
                            {{ post.content }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card card-widget">
                    <div class="card-header">
                        <span class="username">COMMENT CONTENT</span>
                    </div>
                    <div class="card-footer card-comments" v-if="comments" style="height: 42rem; overflow: auto;">
                        <div class="card-comment">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" v-for="(comment, index) in comments.data" :key="index">
                                        <div class="media" style="margin-top: 10px;">
                                            <a target="_blank" :href="'/accounts/profile/'+comment.users.id">
                                                <img class="mr-3 rounded-circle" :src="comment.users.avatar ? comment.users.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" />
                                            </a>
                                            <div class="media-body">
                                                <div class="row">
                                                    <div class="col-md-12 d-flex">
                                                        <a target="_blank" :href="'/accounts/profile/'+comment.users.id">
                                                            {{ comment.users.name }}
                                                        </a>
                                                        <span> - {{ comment.created_at }} - {{ comment.total_like }} <i class="fas fa-thumbs-up"></i></span>
                                                    </div>
                                                </div>
                                                <p style="margin-bottom: 0rem;" v-html="replaceMention(comment.content, comment.mentions) "></p>
                                                <div class="col-md-12" v-for="(img, index) in comment.images" :index="index" :key="'A'+ index">
                                                    <viewer :options="options">
                                                        <img :src="img.image_path" style="width:50%; height:50%; border-radius: 15px;">
                                                    </viewer>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="media mt-4" v-for="(child_comment, j) in comment.child_comments" :key="j">
                                                        <a target="_blank" :href="'/accounts/profile/'+child_comment.users.id">
                                                            <img class="rounded-circle" :src="child_comment.users.avatar ? child_comment.users.avatar : 'https://s3-comuni.s3-ap-southeast-1.amazonaws.com/file/1599188010/avatar1.jpg'" />
                                                        </a>
                                                    <div class="media-body">
                                                        <div class="row">
                                                            <div class="col-md-12 d-flex">
                                                                <a target="_blank" :href="'/accounts/profile/'+child_comment.users.id">
                                                                    {{ child_comment.users.name }}
                                                                </a>
                                                                <span> - {{ child_comment.created_at }} - {{ child_comment.total_like }} <i class="fas fa-thumbs-up"></i></span>
                                                            </div>
                                                        </div>
                                                        <p style="margin-bottom: 0rem;" v-html="replaceMention(child_comment.content, child_comment.mentions)">
                                                        </p>
                                                        <div class="col-md-12" v-for="(img, index) in child_comment.images" :index="index" :key="'A'+ index">
                                                            <viewer :options="options">
                                                                <img :src="img.image_path" style="width:50%; height:50%; border-radius: 15px;">
                                                            </viewer>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="comments">
                        <div class="col-sm-12 col-md-7">
                            <pagination :data="comments" @pagination-change-page="getCommentPost($event)" class="float-right"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapState } from 'vuex';
import Pagination from 'laravel-vue-pagination';
import Media from '@dongido/vue-viaudio';
import { Carousel3d, Slide } from 'vue-carousel-3d';

export default {
    name: 'PostDetail',
    components: {
        Carousel3d,
        Slide,
        Media,
        Pagination,
    },
    data() {
        return {
            query: {
                page: 1,
            },
            slug: this.$route.params.slug,
            options: {
                'title': false
            }
        };
    },

    watch: {
        //
    },

    computed: {
        ...mapState({
            post: state => state.storePosts.post,
            comments: state => state.storePosts.comments
        }),
    },

    created() {
        this.$store.commit('SET_ACTIVE_ASIDE', {
            aside: 'posts'
        });

        this.showDetailPost();
        this.getCommentPost();
    },

    methods: {
        async showDetailPost() {
            await this.$store.dispatch('actionDetailPost', {
                vue: this,
                slug: this.slug,
            });
        },

        async getCommentPost(page) {
            let query = this.query;
            if (page) {
                query.page = page;
            }

            await this.$store.dispatch('actionGetCommentPost', {
                vue: this,
                slug: this.slug,
                page: query
            });
        },

        replaceMention(comment, users) {
            if (users.length) {
                users.forEach(function(item) {
                    let re = new RegExp('<mention>'+ item.id + '</mention>', 'g');
                    comment = comment.replace(re, '<a target="_blank" href="/accounts/profile/'+ item.id + '">'+item.name+'</a>');
                });
            }

            return comment;
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        }
    }
};
</script>


<style lang="css">
.media img {
    width: 60px;
    height: 60px
}

.reply a {
    text-decoration: none
}

.badge {
    white-space: unset !important;
}
</style>
