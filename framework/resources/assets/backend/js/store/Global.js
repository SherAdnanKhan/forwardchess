import _ from 'lodash';
import * as actionNames from '../dictionary/action-names';
import * as mutationNames from '../dictionary/mutation-names';
import HttpClient from '../../../common/utils/HttpClient';

const state = {
    user      : null,
    publishers: {},
    categories: {},
    tags      : {},
    countries : []
};

const getters = {
    user      : state => state.user,
    publishers: state => state.publishers,
    categories: state => state.categories,
    tags      : state => state.tags,
    countries : state => state.countries
};

const client = HttpClient();

const actions = {
    [actionNames.SET_USER]({commit}, {user}) {
        commit(mutationNames.SET_USER, user);
    },

    [actionNames.SET_PUBLISHERS]({commit}, {publishers}) {
        commit(mutationNames.SET_PUBLISHERS, publishers);
    },

    [actionNames.SET_CATEGORIES]({commit}, {categories}) {
        commit(mutationNames.SET_CATEGORIES, categories);
    },

    [actionNames.SET_TAGS]({commit}, {tags}) {
        commit(mutationNames.SET_TAGS, tags);
    },

    [actionNames.SET_COUNTRIES]({commit}, {countries}) {
        commit(mutationNames.SET_COUNTRIES, countries);
    },

    [actionNames.SET_PUBLISHER]({state, dispatch}, publisher) {
        const publishers = _.extend({}, state.publishers, {
            [publisher.id]: _.extend({}, publisher)
        });

        dispatch(actionNames.SET_PUBLISHERS, {publishers});
    },

    [actionNames.SET_CATEGORY]({state, dispatch}, category) {
        const categories = _.extend({}, state.categories, {
            [category.id]: _.extend({}, category)
        });

        dispatch(actionNames.SET_CATEGORIES, {categories});
    },

    [actionNames.SET_TAG]({state, dispatch}, tag) {
        const tags = _.extend({}, state.tags, {
            [tag.id]: _.extend({}, tag)
        });

        dispatch(actionNames.SET_TAGS, {tags});
    },

    [actionNames.SET_STATES]({commit}, {countryCode, states}) {
        commit(mutationNames.SET_STATES, {countryCode, states});
    },

    [actionNames.LOAD_PUBLISHERS]({dispatch}) {
        return client.get('publishers').then(data => {
            const publishers = {};
            _.each(data, publisher => {
                publishers[publisher.id] = publisher;
            });

            dispatch(actionNames.SET_PUBLISHERS, {publishers});

            return publishers;
        });
    },

    [actionNames.LOAD_CATEGORIES]({dispatch}) {
        return client.get('categories').then(data => {
            const categories = {};
            _.each(data, category => {
                categories[category.id] = category;
            });

            dispatch(actionNames.SET_CATEGORIES, {categories});

            return categories;
        });
    },

    [actionNames.LOAD_TAGS]({dispatch}) {
        return client.get('tags').then(data => {
            const tags = {};
            _.each(data, tag => {
                tags[tag.id] = tag;
            });

            dispatch(actionNames.SET_TAGS, {tags});

            return tags;
        });
    },

    [actionNames.LOAD_COUNTRIES]({dispatch}) {
        return client.get('countries').then(data => {
            const countries = {};
            _.each(data, country => {
                countries[country.code] = {
                    ...country,
                    states: []
                };
            });

            dispatch(actionNames.SET_COUNTRIES, {countries});

            return countries;
        });
    }
};

const mutations = {
    [mutationNames.SET_USER](state, user) {
        state.user = user;
    },

    [mutationNames.SET_PUBLISHERS](state, publishers) {
        state.publishers = publishers;
    },

    [mutationNames.SET_CATEGORIES](state, categories) {
        state.categories = categories;
    },

    [mutationNames.SET_TAGS](state, tags) {
        state.tags = tags;
    },

    [mutationNames.SET_COUNTRIES](state, countries) {
        state.countries = countries;
    },

    [mutationNames.SET_STATES](state, {countryCode, states}) {
        if (state.countries.hasOwnProperty(countryCode)) {
            state.countries[countryCode].states = states;
        }
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
