<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <section class="panel">
      <header class="panel-heading">
        <h2 class="panel-title">Data</h2>
      </header>
      <div class="panel-body">
        <component
            :is="tableName"
            :url="listApiUrl"
            :data="tableData"
            :columns="tableColumns"
            :options="tableOptions"
            ref="table"
        />
          <!-- <div slot="title" slot-scope="props">
            <a href="#" @click="go(props.row.url)" class="pull-left mr-10">{{props.row.title}}</a>
          </div>
        </component> -->
      </div>
    </section>
  </div>
</template>

<script>
import ListingPage from '../../layout/ListingPage.vue';
import {WISHLISTS_NAME} from '../../../router/names';

export default {
  extends: ListingPage,

  data() {
    return {
      pageName    : WISHLISTS_NAME,
      resourceUrl : 'wishlists',
      tableColumns: ['title', 'nrUsers'],
      tableOptions: {
        orderBy: {
          column   : 'nrUsers',
          ascending: false
        }
      }
    };
  },

  methods:{
    go(url){
      window.location.href = `/product/${url}`
    }
  }
};
</script>