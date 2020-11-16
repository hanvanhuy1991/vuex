import axios from 'axios';

/**
 * AuthService
 */
export default class authService {

    /**
     * @param { object } user
     *
     * @returns {AxiosPromise<any>}
     */
    static login(credentials) {
        return axios.post('/api/v1/login', credentials)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * @param { string } email
     *
     * @returns {AxiosPromise<any>}
     */
    static forgotPassword(params) {
        return axios.post('/api/v1/forgot-password', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * @returns {AxiosPromise<any>}
     */
    static getProfile() {
        return axios.get('/api/v1/profile')
            .then(response => response)
            .catch(error => error);
    }

    /**
     * @returns {AxiosPromise<any>}
     */
    static updateProfile(params) {
        return axios.put(`/api/v1/profile/${params.id}`, params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * @returns {AxiosPromise<any>}
     */
    static changePassword(params) {
        return axios.post('/api/v1/change-password/', params)
            .then(response => response)
            .catch(error => error);
    }

    /**
     * @returns {AxiosPromise<any>}
     */
    static logout() {
        return axios.get('/api/v1/logout')
            .then(response => response)
            .catch(error => error);
    }
}