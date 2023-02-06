<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <component
        v-if="modalName && modalState"
        :is="modalName"
        :state="modalState"
        :modelId="modelId"
        :resourceUrl="resourceUrl"
        @saved="refreshList"
        @close="closeModal"
    />

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
            ref="table">
          <div slot="logo" slot-scope="props">
            <img :src="props.row.logo" width="100"/>
          </div>
          <div slot="actions" slot-scope="props">
            <a href="#" @click.prevent="onEdit(props.row.code)" class="pull-left mr-10">
              <i class="fa fa-edit"></i>
              Edit
            </a>&nbsp;
          </div>
        </component>
      </div>
    </section>
  </div>
</template>

<script>
import {TAX_RATES_NAME} from '../../../router/names';
import ListingPage from '../../layout/ListingPage.vue';
import TaxRateModal from '../../modals/TaxRateModal';

export default {
  extends: ListingPage,

  components: {
    TaxRateModal
  },

  data() {
    return {
      pageName    : TAX_RATES_NAME,
      resourceUrl : 'tax-rates',
      tableColumns: ['code', 'country', 'rate', 'name', 'actions'],
      modalName   : 'TaxRateModal',
      tableOptions: {
        orderBy : {
          column: 'code'
        }
      }
    };
  }
};
</script>