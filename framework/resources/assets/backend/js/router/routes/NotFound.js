import NotFound from '../../components/pages/NotFound';
import {NOT_FOUND_NAME} from '../names';

export default {
    name     : NOT_FOUND_NAME,
    path     : '*',
    component: NotFound,
    meta     : {
        title: 'Not found'
    }
};
