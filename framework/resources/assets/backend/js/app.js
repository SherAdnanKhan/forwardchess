import './bootstrap';
import theme from './bootstrap/theme';
import Vue from 'vue';
import router from './router';
import store from './store';
import {LOAD_PUBLISHERS, LOAD_CATEGORIES, LOAD_TAGS, LOAD_COUNTRIES, SET_USER} from './dictionary/action-names';
import ApiClient from '../../common/providers/ApiClient';

$(document).ready(function () {
    theme();

    Promise.all([
        store.dispatch(LOAD_PUBLISHERS),
        store.dispatch(LOAD_CATEGORIES),
        store.dispatch(LOAD_TAGS),
        store.dispatch(LOAD_COUNTRIES)
    ]).then(() => {
        store.dispatch(SET_USER, {user: window.user});

        new Vue({
            store,
            router,
            el     : '#app',
            provide: {
                api: ApiClient
            }
        });
    });
});
