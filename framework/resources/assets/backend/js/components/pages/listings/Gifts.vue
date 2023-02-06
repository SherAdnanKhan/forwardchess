<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <section class="panel">
      <header class="panel-heading">
        <h2 class="panel-title">Data</h2>
        <div class="panel-actions">
          <button type="button" class="btn btn-primary pull-right" @click="onAdd">
            <i class="fa fa-plus"></i>
            Add
          </button>
        </div>
      </header>
      <div class="panel-body">
        <component
            :is="tableName"
            :url="listApiUrl"
            :data="tableData"
            :columns="tableColumns"
            :options="tableOptions"
            ref="table">
          <div slot="buyer" slot-scope="props">
            <router-link :to="userRouteParams(props.row.userId)">{{ props.row.buyer }}</router-link>
          </div>
          <div slot="code" slot-scope="props">
            <router-link :to="editRouteParams(props.row.id)">{{ props.row.code }}</router-link>
          </div>
          <div slot="amount" slot-scope="props">
            ${{ props.row.amount }}
          </div>
        </component>
      </div>
    </section>
  </div>
</template>

<script>
import ListingPage from '../../layout/ListingPage.vue';
import {GIFT_NAME, GIFTS_NAME, USER_NAME} from '../../../router/names';

export default {
  extends: ListingPage,

  data() {
    return {
      pageName    : GIFTS_NAME,
      resourceUrl : 'gifts',
      tableColumns: ['created_at', 'expireDate', 'buyer', 'code', 'amount'],
      tableOptions: {
        headings: {
          created_at: 'Created at',
          expireDate: 'Expires at'
        },
        orderBy : {
          column   : 'created_at',
          ascending: false
        }
      },
      allowDelete : false
    };
  },

  methods: {
    onAdd() {
      this.$router.push({
        name  : GIFT_NAME,
        params: {
          gift: 'add'
        }
      });
    },

    editRouteParams(giftId) {
      return {
        name  : GIFT_NAME,
        params: {
          gift: String(giftId)
        }
      };
    },

    userRouteParams(userId) {
      return {
        name  : USER_NAME,
        params: {
          user: String(userId)
        }
      };
    }
  }
};
</script>