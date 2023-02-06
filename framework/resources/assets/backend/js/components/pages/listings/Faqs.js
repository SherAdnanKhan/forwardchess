import ListingPage from '../../layout/ListingPage.vue';
import FaqsFilters from '../../filters/FaqsFilters';
import {FAQ_NAME, FAQS_NAME} from '../../../router/names';

export default {
  extends: ListingPage,

  components: {
    FaqsFilters
  },

  data() {
    return {
      pageName    : FAQS_NAME,
      resourceUrl : 'faq',
      filtersName : 'FaqsFilters',
      tableColumns: ['created_at', 'categoryName', 'question', 'position', 'actions'],
      tableOptions: {
        headings: {
          created_at  : 'Created at',
          categoryName: 'Category'
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
        name  : FAQ_NAME,
        params: {
          post: 'add'
        }
      });
    },

    onEdit(postId) {
      this.$router.push({
        name  : FAQ_NAME,
        params: {
          post: postId
        }
      });
    }
  }
};
