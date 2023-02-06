import _ from 'lodash';
import PageHeader from '../layout/PageHeader.vue';
import {DASHBOARD_PATH} from '../../router/paths';

export default {
    components: {
        PageHeader
    },

    data() {
        return {
            pageTitle  : '',
            breadcrumbs: [
                {url: DASHBOARD_PATH, icon: 'fa fa-home'}
            ]
        };
    },

    methods: {
        setTitle(title) {
            this.pageTitle = title;

            return this;
        },

        setBreadcrumbs(breadcrumbs) {
            if (!_.isArray(breadcrumbs)) {
                breadcrumbs = [breadcrumbs];
            }

            this.breadcrumbs = this.breadcrumbs.concat(breadcrumbs);

            return this;
        }
    }
};