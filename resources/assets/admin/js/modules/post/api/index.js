/**
 * @export
 * @class PostService
 */
export default class PostService {
    /**
     * Return List Spend
     *
     * @returns {AxiosPromise<any>}
     */
    static getPosts(params) {
        let options = {
            method: 'GET',
            url: `/api/v1/posts?page=${params.page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Return List Account
     *
     * @returns {AxiosPromise<any>}
     */
    static getAccounts() {
        let options = {
            method: 'GET',
            url: '/api/v1/search-accounts',
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Search Post
     *
     * @returns {AxiosPromise<any>}
     */
    static searchPosts(params, page) {
        let options = {
            method: 'GET',
            params: params,
            url: `/api/v1/search-posts?page=${page.page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Detail Post
     *
     * @returns {AxiosPromise<any>}
     */
    static getDetailPost(slug) {
        let options = {
            method: 'GET',
            url: `/api/v1/posts/${slug}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * Get comment for Post
     *
     * @returns {AxiosPromise<any>}
     */
    static getCommentPost(slug, page) {
        let options = {
            method: 'GET',
            url: `/api/v1/posts/${slug}/comments?page=${page.page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }
}