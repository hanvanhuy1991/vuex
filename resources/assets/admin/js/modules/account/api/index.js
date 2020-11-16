/**
 * @export
 *
 * @class AccountService
 */
export default class AccountService {

    /**
     * GetListAccounts
     *
     * @returns {AxiosPromise<any>}
     */
    static getListAccounts(params, page) {
        let options = {
            method: 'GET',
            params: params,
            url: `/api/v1/accounts?page=${page.page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * getBusinessCategory
     *
     * @returns {AxiosPromise<any>}
     */
    static getBusinessCategory() {
        let options = {
            method: 'GET',
            url: 'api/v1/list-business',
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * getDetailAccount
     *
     * @returns {AxiosPromise<any>}
     */
    static getDetailAccount(id) {
        let options = {
            method: 'GET',
            url: `/api/v1/accounts/${id}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * getDetailAccount
     *
     * @returns {AxiosPromise<any>}
     */
    static getListPosts(id, page) {
        let options = {
            method: 'GET',
            url: `/api/v1/accounts/${id}/posts?page=${page}`,
            json: true,
        };

        return axios(options)
            .then(response => response)
            .catch(error => error);
    }
}