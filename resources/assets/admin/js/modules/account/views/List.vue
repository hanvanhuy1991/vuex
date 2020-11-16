<template>
<section class="content" v-if="listAccountsCms">
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
                    <form role="form" v-on:keydown.enter.prevent="searchAccounts()">
                        <div class="row">
                            <div class="col-sm-6" v-if="accounts">
                                <!-- select -->
                                <div class="form-group">
                                    <label>{{ $t('account.accounts') }}</label>
                                    <multiselect v-model="listAccounts" :tag-placeholder="$t('posts.search_this_new_a_account')" :placeholder="$t('posts.search_this_new_a_account')" label="name" track-by="code" :options="accounts" :multiple="true" :taggable="true"></multiselect>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> {{ $t('account.email') }}</label>
                                    <input type="email" placeholder="Email"  v-model="search.email" class="form-control" v-bind:class="{'is-invalid': errors && objectNotEmpty(errors.email)}">
                                    <span v-if="errors && objectNotEmpty(errors.email)" class="error invalid-feedback">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>{{ $t('account.phone') }}</label>
                                    <input type="text" placeholder="Phone" v-model="search.phone" class="form-control" v-bind:class="{'is-invalid': errors && objectNotEmpty(errors.phone)}">
                                    <span v-if="errors && objectNotEmpty(errors.phone)" class="error invalid-feedback">{{ errors.phone[0] }}</span>

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>{{ $t('account.type') }}</label>
                                    <select class="form-control" v-model="search.type">
                                        <option value="">{{ $t('common.please_choose_type') }}</option>
                                        <option value="business_account">{{ $t('account.office_business') }}</option>
                                        <option value="unicer">{{ $t('account.unicer') }}</option>
                                        <option value="influencer">{{ $t('account.influencer') }}</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-sm-6" v-if="businessCategories">
                                <!-- select -->
                                <div class="form-group">
                                    <label v-bind:class="{ 'disabled': disableSelectCategory }" >{{ $t('account.category') }}</label>
                                    <multiselect  v-model="listCategories" :tag-placeholder="$t('account.search_this_new_a_category')" :placeholder="$t('account.search_this_new_a_category')" label="name" track-by="code" :options="businessCategories" :multiple="true" :taggable="true"></multiselect>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group"><label>{{ $t('account.status') }}</label>
                                    <select v-model="search.status" class="form-control">
                                        <option value="">{{ $t('common.please_choose_status') }}</option>
                                        <option value="1">{{ $t('account.active') }}</option>
                                        <option value="2">{{ $t('account.locked') }}</option>
                                        <option value="3">{{ $t('account.temporary_locked') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" @click="searchAccounts()" class="btn btn-primary float-right">
                                <i class="fas fa-search"></i>
                                {{ $t('common.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body p-0" style="display: block;">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 5%">
                            #
                        </th>
                        <th>
                            {{ $t('account.name') }}
                        </th>
                        <th>
                            {{ $t('account.type') }}
                        </th>
                        <th>
                            {{ $t('account.category') }}
                        </th>
                         <th class="text-center">
                            {{ $t('account.phone') }}
                        </th>
                        <th class="text-center">
                            {{ $t('account.following') }}
                        </th>
                        <th class="text-center">
                            {{ $t('account.followers') }}
                        </th>
                        <th class="text-center">
                            {{ $t('common.status') }}
                        </th>
                        <th class="text-center">
                            {{ $t('common.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(account, index) in listAccountsCms.data" :key="index">
                        <td>
                            # {{ index + 1 }}
                        </td>
                        <td>
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" :src="account.avatar">  {{ account.name }}
                            </li>
                            <br>
                            <small>
                                {{ $t('common.created_at') }} : {{ account.created_at }}
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <span class="badge badge-success" v-for="(role, index) in account.role" :key="index">{{ role.name }}</span>
                            </ul>
                        </td>
                        <td>
                            <span class="badge badge-secondary"  v-for="(category, index) in account.categories" :key="index">{{ category.name }} </span>
                        </td>
                        <td class="project-state text-center" >
                            {{ account.phone }}
                        </td>
                        <td class="project-state">
                            <span class="badge badge-secondary">{{ account.total_following }}</span>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-secondary">{{ account.total_followers }}</span>
                        </td>
                        <td class="project-state">
                            <span :class="[account.status == 1 ? 'badge-success' : account.status == 2 ? 'badge-danger' : 'badge-warning']" class="badge">{{ account.status == 1 ? $t('account.active') : account.status == 2 ? $t('account.locked') : $t('account.temporary_locked') }}</span>
                        </td>
                        <td class="project-actions text-center">
                            <router-link :to="{ name: 'Profile', params: { id: account.id }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                {{ $t('common.view') }}
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-5">
            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">{{ $t('posts.showing') }} {{ listAccountsCms.meta.from }} {{ $t('posts.to') }} {{ listAccountsCms.meta.to }} {{ $t('posts.of') }} {{ listAccountsCms.meta.total }} {{ $t('posts.entries') }}</div>
        </div>
        <div class="col-sm-12 col-md-7">
            <pagination :data="listAccountsCms" @pagination-change-page="searchAccounts($event)" class="float-right">
            </pagination>
        </div>
    </div>
</section>
</template>

<script>
import {
    mapState
} from 'vuex';

import Pagination from 'laravel-vue-pagination';
import Multiselect from 'vue-multiselect';

export default {
    name: 'AccountList',
    components: {
        Pagination,
        Multiselect
    },

    data() {
        return {
            query: {
                page: 1,
            },
            isDisabled: false,
            search: {
                account_id: '',
                email: null,
                phone: null,
                type: '',
                category_id: '',
                status: ''
            },
            listAccounts: null,
            listCategories: null
        };
    },

    created() {
        this.$store.commit('SET_ACTIVE_ASIDE', {
            aside: 'users'
        });
        this.getListAccount();
        this.getBusinessCategory();
        this.searchAccounts();
    },

    computed: {
        ...mapState({
            listAccountsCms: state => state.storeAccounts.listAccounts,
            businessCategories: state => state.storeAccounts.businessCategories,
            accounts: state => state.storePosts.accounts,
            errors: state => state.storeAccounts.errors
        }),

        disableSelectCategory() {
            if (this.search.type === 'business_account' || this.search.type === 'influencer') {
                return false;
            }

            return true;
        }
    },

    watch: {
        disableSelectCategory: {
            deep: true,
            handler: function (val) {
                if (val) {
                    this.listCategory = null;
                }
            }
        },

        'search.email'() {
            if (this.errors) {
                this.errors.email = null;
            }
        },

        'search.phone'() {
            if (this.errors) {
                this.errors.phone = null;
            }
        },
    },

    methods: {
        async searchAccounts(page) {
            let query = this.query;
            if (page) {
                query.page = page;
            }

            if (this.listAccounts) {
                let array = [];
                this.listAccounts.forEach(function(item) {
                    array.push(item.code);
                });
                this.search.account_id = array;
            }

            if (this.listCategories) {
                let array = [];
                this.listCategories.forEach(function(item) {
                    array.push(item.code);
                });
                this.search.category_id = array;
            }

            if (this.search.email === '') {
                this.search.email = null;
            }

            if (this.search.phone === '') {
                this.search.phone = null;
            }

            await this.$store.dispatch('actionSearchAccounts', {
                vue: this,
                params: this.search,
                page: this.query
            });
        },

        async getListAccount() {
            await this.$store.dispatch('actionGetAccount', {
                vue: this,
            });
        },

        async getBusinessCategory() {
            await this.$store.dispatch('actionGetBusinessCategory', {
                vue: this,
            });
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        }
    }
};
</script>

<style lang="css">
    label.disabled { color: #aaa; }
</style>
