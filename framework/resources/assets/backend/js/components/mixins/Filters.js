import _ from 'lodash';
import {isEmpty} from '../../../../common/utils/Library';
import CustomSelect from '../../../../common/components/CustomSelect';

export default {
    components: {
        CustomSelect
    },

    data() {
        return {
            filters: this.getDefaultFilters()
        };
    },

    created() {
        _.each(_.keys(this.filters), filterName => {
            this.$watch(`filters.${filterName}`, () => this.filterChanged(filterName));
        });

        this.$emit('init', _.keys(this.filters));
    },

    methods: {
        getDefaultFilters() {
            return {}
        },

        getFilter(name, value) {
            return value;
        },

        filterChanged(name) {
            const value = this.filters[name];
            this.$emit('changed', name, isEmpty(value) ? value : this.getFilter(name, value));
        },

        reset() {
            this.filters = this.getDefaultFilters();
        }
    }
};