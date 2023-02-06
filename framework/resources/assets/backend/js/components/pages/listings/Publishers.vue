<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <component
        v-if="filtersName"
        :is="filtersName"
        @init="setFilters"
        @changed="applyFilters"
    />

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
        <div class="panel-actions">
          <button v-if="allowAdd" type="button" class="btn btn-primary pull-right" @click="onAdd">
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
          <div slot="logo" slot-scope="props">
            <img :src="props.row.logo" width="100"/>
          </div>
          <div slot="actions" slot-scope="props">
            <a href="#" @click.prevent="onEdit(props.row.id)" class="pull-left mr-10">
              <i class="fa fa-edit"></i>
              Edit
            </a>&nbsp;
            <div v-if="allowDelete" class="pull-left">
              <a v-if="props.row.deleted_at" href="#" @click.prevent="onRestore(props.row.id)">
                <i class="fa fa-undo"></i>
                Restore
              </a>
              <a v-else href="#" @click.prevent="onDelete(props.row.id)">
                <i class="fa fa-remove"></i>
                Delete
              </a>
            </div>
          </div>
        </component>
      </div>
    </section>
  </div>
</template>

<script>
import _ from 'lodash';
import {mapActions, mapGetters} from 'vuex';
import ListingPage from '../../layout/ListingPage.vue';
import PublisherModal from '../../modals/PublisherModal';
import {PUBLISHERS_NAME} from '../../../router/names';
import {SET_PUBLISHERS} from '../../../dictionary/action-names';

export default {
  extends: ListingPage,

  components: {
    PublisherModal
  },

  computed: {
    ...mapGetters([
      'publishers'
    ]),

    tableData() {
      const data = [];
      _.each(this.publishers, publisher => {
        data.push(publisher);
      });

      return data;
    }
  },

  data() {
    return {
      pageName        : PUBLISHERS_NAME,
      resourceUrl     : 'publishers',
      tableName       : 'v-client-table',
      modalName       : 'PublisherModal',
      tableColumns    : ['logo', 'name', 'position', 'actions'],
      tableRowsPerPage: 10,
      tableOptions    : {
        orderBy: {
          column   : 'position',
          ascending: true
        }
      }
    };
  },

  methods: {
    ...mapActions({
      'setPublishers': SET_PUBLISHERS
    }),

    async onDelete(modelId) {
      if (confirm('Are you sure you want to delete this record?')) {
        try {
          await this.api.client.delete(`${this.resourceUrl}/${modelId}`);

          const publishers = _.extend({}, this.publishers);
          delete publishers[modelId];
          this.setPublishers({publishers});
        } catch (error) {
          this.$snotify.error(error.message);
        }
      }
    }
  }
};
</script>
