import _ from 'lodash';
import {mapActions, mapGetters} from 'vuex';
import ListingPage from '../../layout/ListingPage.vue';
import CategoryModal from '../../modals/CategoryModal.vue';
import {CATEGORIES_NAME} from '../../../router/names';
import {SET_CATEGORIES} from '../../../dictionary/action-names';

export default {
  extends: ListingPage,

  components: {
    CategoryModal
  },

  computed: {
    ...mapGetters([
      'categories'
    ]),

    tableData() {
      const data = [];
      _.each(this.categories, category => {
        data.push(category);
      });

      return data;
    }
  },

  data() {
    return {
      pageName    : CATEGORIES_NAME,
      resourceUrl : 'categories',
      modalName   : 'CategoryModal',
      tableName   : 'v-client-table',
      tableColumns: ['name', 'position', 'actions']
    };
  },

  methods: {
    ...mapActions({
      'setCategories': SET_CATEGORIES
    }),

    async onDelete(modelId) {
      if (confirm('Are you sure you want to delete this record?')) {
        try {
          await this.api.client.delete(`${this.resourceUrl}/${modelId}`);

          const categories = _.extend({}, this.categories);
          delete categories[modelId];
          this.setCategories({categories});
        } catch (error) {
          this.$snotify.error(error.message);
        }
      }
    }
  }
};
