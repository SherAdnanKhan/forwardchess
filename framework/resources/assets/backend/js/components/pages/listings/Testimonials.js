import ListingPage from '../../layout/ListingPage.vue';
import {TESTIMONIAL_NAME, TESTIMONIALS_NAME} from '../../../router/names';

export default {
    extends: ListingPage,

    data() {
        return {
            pageName    : TESTIMONIALS_NAME,
            resourceUrl : 'testimonials',
            tableColumns: ['created_at', 'user', 'actions'],
            tableOptions: {
                headings: {
                    created_at: 'Created at',
                },
                orderBy : {
                    column   : 'created_at',
                    ascending: false
                }
            }
        };
    },

    methods: {
        onAdd() {
            this.$router.push({
                name  : TESTIMONIAL_NAME,
                params: {
                    post: 'add'
                }
            });
        },

        onEdit(postId) {
            this.$router.push({
                name  : TESTIMONIAL_NAME,
                params: {
                    post: postId
                }
            });
        }
    }
};
