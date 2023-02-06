import {DASHBOARD_PATH} from '../paths';
import {DASHBOARD_NAME} from '../names';
import Dashboard from '../../components/pages/Dashboard.vue';

export default {
    component: Dashboard,
    name     : DASHBOARD_NAME,
    path     : DASHBOARD_PATH,
    meta     : {
        title: 'Dashboard'
    }
};
