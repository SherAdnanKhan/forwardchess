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
          <div slot="active" slot-scope="props">
            {{props.row.active ? 'yes' : 'no'}}
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
import ListingPage from '../../layout/ListingPage';
import ArticlesFilters from '../../filters/ArticlesFilters';
import {BLOG_ARTICLE_NAME, BLOG_ARTICLES_NAME} from '../../../router/names';

export default {
  extends: ListingPage,

  components: {
    ArticlesFilters
  },

  data() {
    return {
      pageName    : BLOG_ARTICLES_NAME,
      resourceUrl : 'articles',
      filtersName : 'ArticlesFilters',
      tableColumns: ['publishDate', 'title', 'active', 'actions']
    };
  },

  methods: {
    onAdd() {
      this.$router.push({
        name  : BLOG_ARTICLE_NAME,
        params: {
          article: 'add'
        }
      });
    },

    onEdit(article) {
      this.$router.push({
        name  : BLOG_ARTICLE_NAME,
        params: {
          article
        }
      });
    }
  }
};
</script>
