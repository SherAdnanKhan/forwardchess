<template>
  <div class="reviews-list">
    <section class="panel">
      <header v-if="title" class="panel-heading">
        <h2 class="panel-title">{{ title }}</h2>
      </header>

      <div class="panel-body">
        <v-server-table
            :url="listApiUrl"
            :columns="tableColumns"
            :options="tableOptions"
            ref="table"
        >
          <div slot="nickname" slot-scope="{ row }">
            <router-link :to="`users/${row.userId}`">{{ row.nickname }}</router-link>
          </div>

          <div slot="productName" slot-scope="{ row }">
            <router-link :to="`products/${row.productId}`">{{ row.productName }}</router-link>
          </div>

          <div slot="approved" slot-scope="{ row }">
            {{ row.approved ? 'Yes' : 'No' }}
          </div>

          <div slot="child_row" slot-scope="{ row }">
            <ReviewsDetails :review="row" @changed="onChanged"/>
          </div>
        </v-server-table>
      </div>
    </section>
  </div>
</template>

<script>
import _ from 'lodash';
import ReviewsDetails from './ReviewsDetails';

export default {
  name: 'ReviewsList',

  components: {
    ReviewsDetails
  },

  props: {
    title: {
      type   : String,
      default: null
    },

    productId: {
      type   : Number,
      default: null
    },

    approved: {
      type   : Boolean,
      default: null
    },

    tableColumns: {
      type   : Array,
      default: () => ([
        'created_at',
        'nickname',
        'productName',
        'title',
        'rating',
        'approved'
      ])
    },

    tableOptions: {
      type   : Object,
      default: () => ({
        headings: {
          created_at : 'Added at',
          productName: 'Product'
        },

        filterable: ['nickname', 'title'],

        orderBy: {
          column   : 'created_at',
          ascending: false
        }
      })
    }
  },

  computed: {
    listApiUrl() {
      const query = [];
      if (this.productId) {
        query.push(`productId=${this.productId}`);
      }

      if (_.isBoolean(this.approved)) {
        query.push(`approved=${this.approved ? 1 : 0}`);
      }

      return `${window.apiBaseURL}/reviews/tables` + (query.length ? `?${query.join('&')}` : '');
    }
  },

  methods: {
    onChanged() {
      this.$refs.table.refresh();
    }
  }
};
</script>