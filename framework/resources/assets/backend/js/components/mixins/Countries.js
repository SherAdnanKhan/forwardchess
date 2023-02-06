import _ from 'lodash';
import {mapActions, mapGetters} from 'vuex';
import {SET_STATES} from "../../dictionary/action-names";

export default {
    inject: ['api'],

    computed: {
        ...mapGetters({
            'storeCountries': 'countries'
        }),

        countries() {
            return _.values(this.storeCountries);
        },
    },

    data() {
        return {
            states: [],
        }
    },

    methods: {
        ...mapActions({
            'setStates': SET_STATES
        }),

        async getStates(countryCode) {
            let states = [];

            if (countryCode && this.storeCountries.hasOwnProperty(countryCode)) {
                const country = this.storeCountries[countryCode];
                if (!country.states.length) {
                    states = await this.api.client.get(`countries/${countryCode}/states`);
                    await this.setStates({countryCode, states});
                } else {
                    states = country.states;
                }
            }

            this.states = states;
        }
    }
};