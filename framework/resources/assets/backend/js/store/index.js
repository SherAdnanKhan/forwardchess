import Vue from 'vue';
import Vuex from 'vuex';
import Global from './Global';

Vue.use(Vuex);

const strict = (process.env.NODE_ENV !== 'production');

const store = new Vuex.Store({
    ...Global,
    strict: strict
});

export default store;
