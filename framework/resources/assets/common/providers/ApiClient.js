import HttpClient from '../utils/HttpClient';

export default {
    _client: null,

    get client() {
        if (!this._client) {
            this._client = HttpClient();
        }

        return this._client;
    },

    getAuthHeaders() {
        return {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN'    : window['X-CSRF-TOKEN']
        };
    }
};