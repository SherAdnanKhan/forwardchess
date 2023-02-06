<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
      <div slot="main-content">
        <ApiError :error="error"/>

        <div class="panel" v-if="!isEmpty">
          <form id="formModel" role="form" method="post" @submit.prevent="save" autocomplete="off">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12" v-if="model.id">
                  <h3>Details for coupon <span class="text-info">`{{ model.name }}`</span></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <InputField label="Created at" :value="model.created_at" :readonly="true" icon="fa-calendar"/>
                </div>
                <div class="col-lg-6">
                  <InputField label="Updated at" :value="model.updated_at" :readonly="true" icon="fa-calendar"/>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group" :class="{'has-errors': errors.has('type')}">
                    <label>Type</label>
                    <CustomSelect
                        v-model="model.type"
                        name="type"
                        :options="typesList"
                        data-vv-value-path="selected"
                        data-vv-validate-on="input"
                        v-validate="'required'"
                        :tabindex="1"
                    />
                    <span v-show="errors.has('type')" class="error-msg">{{ errors.first('type') }}</span>
                  </div>
                </div>
                <div class="col-lg-6">
                  <InputField
                      label="Name"
                      name="name"
                      icon="fa-pencil-square-o"
                      v-model="model.name"
                      :errorMsg="errors.has('name') ? errors.first('name') : ''"
                      v-validate="'required'"
                      :tabindex="2"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <InputField
                      label="Code"
                      name="code"
                      icon="fa-pencil-square-o"
                      v-model="model.code"
                      :errorMsg="errors.has('code') ? errors.first('code') : ''"
                      v-validate="{ required: true, regex: /^\S*$/ }"
                      :tabindex="3"
                  />
                </div>
                <div class="col-lg-6">
                  <InputField
                      :label="isPercentType ? 'Discount percent' : 'Discount amount'"
                      name="discount"
                      :icon="isPercentType ? 'fa-percent' : 'fa-dollar'"
                      v-model="model.discount"
                      :errorMsg="errors.has('discount') ? errors.first('discount') : ''"
                      v-validate="{ required: true, numeric: true, min_value: 1, max_value: (isPercentType ? 100 : 10000) }"
                      :tabindex="4"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Period</label>
                    <date-picker
                        name="period"
                        range
                        v-model="period"
                        :shortcuts="shortcuts"
                        lang="en"
                        input-class="form-control m-wrap span12"
                        :not-before="new Date()"
                        v-validate="'required'"
                        tabindex="5"></date-picker>
                    <span v-show="errors.has('period')" class="error-msg">{{ errors.first('period') }}</span>
                  </div>
                </div>
                <div class="col-lg-6">
                  <InputField
                      label="Minimum order amount"
                      name="minAmount"
                      icon="fa-pencil-square-o"
                      v-model="model.minAmount"
                      :errorMsg="errors.has('minAmount') ? errors.first('minAmount') : ''"
                      v-validate="'numeric'"
                      data-vv-as="minimum order amount"
                      :tabindex="6"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6">
                  <InputField
                      label="Usage limit"
                      name="usageLimit"
                      icon="fa-pencil-square-o"
                      v-model="model.usageLimit"
                      :errorMsg="errors.has('usageLimit') ? errors.first('usageLimit') : ''"
                      v-validate="'numeric'"
                      data-vv-as="usage limit"
                      :tabindex="7"
                  />
                </div>
                <div class="col-lg-6">
                  <div class="checkbox form-checkbox" style="margin-top: 15px;">
                    <label>
                      <input type="checkbox" v-model="model.uniqueOnUser" tabindex="9">
                      Users can use it just one time
                    </label>
                  </div>
                  <div class="checkbox form-checkbox">
                    <label>
                      <input type="checkbox" v-model="model.excludeDiscounts" tabindex="9">
                      Exclude products with discounts
                    </label>
                  </div>
                  <div class="checkbox form-checkbox">
                    <label>
                      <input type="checkbox" v-model="model.firstPurchase" tabindex="9">
                      First purchase only
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label>Products (leave empty to apply on all products)</label>
                    <CustomSelect
                        v-model="model.products"
                        name="products"
                        :options="products"
                        placeholder="Select products"
                        :multiple="true"
                        :tabindex="8"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 mt-20">
                  <SaveButton :tabindex="9"/>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </EditPage>
  </div>
</template>

<script>
import _ from 'lodash';
import EditForm from '../../mixins/EditForm';
import Route from '../../mixins/Route';
import {COUPON_NAME, COUPONS_NAME} from '../../../router/names';
import {COUPONS_PATH} from '../../../router/paths';
import {formatIntervalForApi, formatIntervalForPicker} from '../../../../../common/utils/Library';

export default {
  inject: [
    'api'
  ],

  mixins: [
    Route,
    EditForm
  ],

  computed: {
    isPercentType() {
      return (this.model.type === 'percent');
    }
  },

  data() {
    return {
      products        : [],
      selectedProducts: [],
      typesList       : [
        {
          id  : 'percent',
          name: 'Percent'
        },
        {
          id  : 'amount',
          name: 'Amount'
        }
      ],

      period   : [],
      shortcuts: [
        {
          text   : 'Today',
          onClick: () => {
            this.period = [new Date(), new Date()];
          }
        }
      ]
    };
  },

  watch: {
    '$route.params.coupon': {
      immediate: true,
      handler  : async function (id) {
        await this.getProducts();

        if (id === 'add') {
          this.createModel();
        } else {
          await this.getModel(id);
        }
      }
    }
  },

  created() {
    this
        .setTitle(COUPON_NAME)
        .setBreadcrumbs([
          {name: COUPONS_NAME, url: COUPONS_PATH},
          {name: COUPON_NAME}
        ]);
  },

  methods: {
    getResourceUrl(id = null) {
      return id ? `coupons/${id}` : 'coupons';
    },

    async getProducts() {
      try {
        const products = await this.api.client.get('products');
        this.products = _.map(products, product => {
          const {id, title} = product;

          return {id, name: title};
        });
      } catch (error) {
        this.$snotify.error(error.message);
      }
    },

    createModel() {
      this.model = {
        type    : this.typesList[0].id,
        products: []
      };
      this.isLoading = false;
    },

    setModel(model) {
      this.model = model;

      if (model) {
        if (model.startDate && model.endDate) {
          this.period = formatIntervalForPicker(model.startDate, model.endDate);
        } else {
          this.period = [];
        }
      }
    },

    transformModel() {
      const {startDate, endDate} = formatIntervalForApi(this.period);

      return _.extend({}, this.model, {
        startDate,
        endDate,
        firstPurchase: !!this.model.firstPurchase
      });
    },

    afterSave(model) {
      if (this.$route.params.coupon === 'add') {
        this.$router.push({
          name  : COUPON_NAME,
          params: {
            coupon: model.id
          }
        });
      }
    }
  }
};
</script>
