import _ from 'lodash';
import {mapActions, mapGetters} from 'vuex';
import ListingPage from '../../layout/ListingPage';
import TagModal from '../../modals/TagModal';
import {BLOG_TAGS_NAME} from '../../../router/names';
import {SET_TAGS} from '../../../dictionary/action-names';

export default {
    extends: ListingPage,

    components: {
        TagModal
    },

    computed: {
        ...mapGetters([
            'tags'
        ]),

        tableData() {
            const data = [];
            _.each(this.tags, tag => {
                data.push(tag);
            });

            return data;
        }
    },

    data() {
        return {
            pageName    : BLOG_TAGS_NAME,
            resourceUrl : 'tags',
            modalName   : 'TagModal',
            tableName   : 'v-client-table',
            tableColumns: ['name', 'actions']
        };
    },

    methods: {
        ...mapActions({
            'setTags': SET_TAGS
        }),

        async onDelete(modelId) {
            if (confirm('Are you sure you want to delete this record?')) {
                try {
                    await this.api.client.delete(`${this.resourceUrl}/${modelId}`);

                    const tags = _.extend({}, this.tags);
                    delete tags[modelId];
                    this.setTags({tags});
                } catch (error) {
                    this.$snotify.error(error.message);
                }
            }
        }
    }
};
