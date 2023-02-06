<template>
  <div>
    <PageHeader :title="pageTitle" :breadcrumbs="breadcrumbs"></PageHeader>

    <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
      <div slot="main-content">
        <div class="tabs tabs-primary">
          <ul class="nav nav-tabs text-right">
            <li :class="{'active': detailsActive}">
              <a href="#" @click.prevent="setActiveTab(1)">Details</a>
            </li>
            <li v-if="showReviews" :class="{'active': reviewsActive}">
              <a href="#" @click.prevent="setActiveTab(2)">Reviews</a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane" :class="{'active': detailsActive}">
              <ApiError :error="error"/>

              <form id="formModel" role="form" method="post" @submit.prevent="save" autocomplete="off" v-if="!isEmpty">
                <div class="panel">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-lg-12" v-if="model.id">
                        <h3>Details for product <a :href="model.url" class="text-info" target="_blank">`{{ model.title }}`</a></h3>
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
                        <InputField label="Total sales" :value="model.totalSales" :readonly="true"/>
                      </div>

                      <div class="col-lg-6">
                        <InputField
                            label="Author"
                            name="author"
                            icon="fa-pencil-square-o"
                            v-model="model.author"
                            :errorMsg="errors.has('author') ? errors.first('author') : ''"
                            v-validate="'required'"
                            :tabindex="1"
                        />
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group" :class="{'has-errors': errors.has('publisher')}">
                          <label>Publisher</label>
                          <CustomSelect
                              v-model="model.publisherId"
                              name="publisher"
                              :options="publishers"
                              placeholder="Pick a publisher"
                              data-vv-value-path="selected"
                              data-vv-validate-on="input"
                              v-validate="'required'"
                              :tabindex="2"
                          />
                          <span v-show="errors.has('publisher')" class="error-msg">{{ errors.first('publisher') }}</span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group" :class="{'has-errors': errors.has('categories')}">
                          <label>Categories</label>
                          <CustomSelect
                              v-model="model.categories"
                              name="categories"
                              :options="categories"
                              :multiple="true"
                              placeholder="Select categories"
                              data-vv-value-path="selected"
                              data-vv-validate-on="input"
                              v-validate="'required'"
                              :tabindex="3"
                          />
                          <span v-show="errors.has('categories')" class="error-msg">{{ errors.first('categories') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <InputField
                            label="Title"
                            name="title"
                            icon="fa-pencil-square-o"
                            v-model="model.title"
                            :errorMsg="errors.has('title') ? errors.first('title') : ''"
                            v-validate="'required'"
                            :tabindex="4"
                        />
                      </div>

                      <div class="col-lg-6">
                        <InputField
                            label="SKU"
                            name="sku"
                            icon="fa-pencil-square-o"
                            v-model="model.sku"
                            :errorMsg="errors.has('sku') ? errors.first('sku') : ''"
                            v-validate="'required'"
                            :tabindex="5"
                        />
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <InputField
                            label="Nr pages"
                            name="nrPages"
                            icon="fa-pencil-square-o"
                            v-model="model.nrPages"
                            :errorMsg="errors.has('nrPages') ? errors.first('nrPages') : ''"
                            v-validate="'required|numeric'"
                            data-vv-as="nr pages"
                            :tabindex="10"
                        />
                      </div>
                      <div class="col-lg-2">
                        <div class="form-group">
                          <label>Publish date</label>
                          <DatePicker
                              v-model="model.publishDate"
                              lang="en"
                              name="publishDate"
                              input-class="form-control m-wrap span12"
                              format="MMMM DD, YYYY"
                              :tabindex="11"
                          />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-2">
                        <div class="checkbox form-checkbox mt-0">
                          <label>
                            <input type="checkbox" v-model="model.active" tabindex="12">
                            Active
                          </label>
                        </div>
                      </div>

                      <div class="col-lg-2">
                        <div class="checkbox form-checkbox mt-0">
                          <label>
                            <input type="checkbox" v-model="model.isBundle" tabindex="13">
                            IsBundle
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="row" v-if="model.isBundle">
                      <div class="col-lg-12">
                        <div class="form-group" :class="{'has-errors': errors.has('bundleProducts')}">
                          <label>Bundle Products</label>
                          <CustomSearch
                              v-model="model.bundleProducts"
                              :url="`products/tables?isBundle=true`"
                              name="bundleProducts"
                              :multiple="true"
                              :isSearchable="true"
                              placeholder="Select Bundle Products"
                              data-vv-value-path="selected"
                              data-vv-validate-on="input"
                              v-validate="'required'"
                              :tabindex="14"
                              :selectedValue="model.bundleProducts"
                              label-property="title"
                              :custom-label="customLabel"
                          />
                          <span v-show="errors.has('bundleProducts')" class="error-msg">{{ errors.first('bundleProducts') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group" :class="{'has-errors': errors.has('level')}">
                          <label>Level</label>
                          <CustomSelect
                              v-model="model.level"
                              name="level"
                              :options="levels"
                              placeholder="Select Level"
                              data-vv-value-path="selected"
                              data-vv-validate-on="input"
                              v-validate="'required'"
                              :tabindex="15"
                          />
                          <span v-show="errors.has('level')" class="error-msg">{{ errors.first('level') }}</span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <InputField
                            label="Price"
                            name="price"
                            icon="fa-dollar"
                            v-model="model.price"
                            :errorMsg="errors.has('price') ? errors.first('price') : ''"
                            v-validate="{ required: !checkIsBundle, decimal: 2 }"
                            :tabindex="16"
                            :disabled="checkIsBundle"
                        />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group" :class="{'has-errors': errors.has('type')}">
                          <label>Discount type</label>
                          <CustomSelect
                              v-model="model.discountType"
                              name="discountType"
                              :options="discountTypesList"
                              data-vv-value-path="selected"
                              data-vv-validate-on="input"
                              :tabindex="17"
                              :disabled="checkIsBundle"
                          />
                          <span v-show="errors.has('type')" class="error-msg">{{ errors.first('type') }}</span>
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <InputField
                            label="Discount"
                            name="discount"
                            icon="fa-dollar"
                            v-model="model.discount"
                            :errorMsg="errors.has('discount') ? errors.first('discount') : ''"
                            v-validate="'decimal:2'"
                            data-vv-as="discount value"
                            :tabindex="18"
                        />
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label>Discount between</label>
                          <DatePicker
                              name="period"
                              range
                              v-model="period"
                              :shortcuts="shortcuts"
                              lang="en"
                              input-class="form-control m-wrap span12"
                              :not-before="new Date()"
                              tabindex="19"
                          />
                          <span v-show="errors.has('period')" class="error-msg">{{ errors.first('period') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-12">
                        <!-- <TextareaField
                            label="Description"
                            name="description"
                            v-model="model.description"
                            :errorMsg="errors.has('description') ? errors.first('description') : ''"
                            v-validate="'required'"
                            :tabindex="20"
                        /> -->
                        <label>Description</label>
                        <vue-editor
                                v-model="model.description"
                                data-vv-name="description"
                                v-validate="'required'"
                        ></vue-editor>
                        <span v-show="errors.has('description')" class="error-msg">{{ errors.first('description') }}</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <croppa
                            v-model="image"
                            :width="362"
                            :height="500"
                            :initial-image="model.image"
                            @file-choose="onImageChanged"
                            @image-remove="onImageChanged">
                        </croppa>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-12 mt-20">
                        <SaveButton :tabindex="21"/>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div v-if="showReviews" class="tab-pane" :class="{'active': reviewsActive}">
              <ReviewsList :product-id="model.id"/>
            </div>
          </div>
        </div>
      </div>
    </EditPage>
  </div>
</template>

<script>
import _ from 'lodash';
import moment from 'moment';
import {mapGetters} from 'vuex';
import {VueEditor} from "vue2-editor";
import EditForm from '../../mixins/EditForm';
import Route from '../../mixins/Route';
import {PRODUCT_NAME, PRODUCTS_NAME} from '../../../router/names';
import {PRODUCTS_PATH} from '../../../router/paths';
import {formatIntervalForApi, formatIntervalForPicker} from '../../../../../common/utils/Library';
import ReviewsList from './../../reviews/ReviewsList';

export default {
  components: {
    ReviewsList,
    VueEditor
  },

  mixins: [
    Route,
    EditForm
  ],

  computed: {
    ...mapGetters({
      'storePublishers': 'publishers',
      'storeCategories': 'categories'
    }),

    publishers() {
      return _.values(this.storePublishers);
    },

    categories() {
      return _.values(this.storeCategories);
    },

    showReviews() {
      return !this.isEmpty && this.model.id;
    },

    detailsActive() {
      return (this.activeTab === 1);
    },

    reviewsActive() {
      return (this.activeTab === 2);
    },

    checkIsBundle() {
      if (this.model.isBundle) {
        this.updateModel();
        return true;
      } else {
        return false;
      }
    }
  },

  data() {
    return {
      selectedPublisher : null,
      selectedCategories: [],

      discountTypesList: [
        {
          id  : 'percent',
          name: 'Percent'
        },
        {
          id  : 'amount',
          name: 'Amount'
        }
      ],

      levels: [
        {name: 'Beginner', id: 'beginner'},
        {name: 'Intermediate', id: 'intermediate'},
        {name: 'Advanced', id: 'advanced'}
      ],

      activeTab   : 1,
      image       : {},
      period      : [],
      imageChanged: false,
      shortcuts   : [
        {
          text   : 'Today',
          onClick: () => {
            this.period = [new Date(), new Date()];
          }
        }
      ],
      bundleBooks : []
    };
  },

  watch: {
    '$route.params.product': {
      immediate: true,
      handler  : async function (id) {
        if (id !== 'add') {
          await this.getModel(id);
        } else {
          this.createModel();
        }
      }
    }
  },

  created() {
    this
        .setTitle(PRODUCT_NAME)
        .setBreadcrumbs([
          {name: PRODUCTS_NAME, url: PRODUCTS_PATH},
          {name: PRODUCT_NAME}
        ]);
  },

  methods: {
    getResourceUrl(id = null) {
      return id ? `products/${id}` : 'products';
    },

    setModel(model) {
      if (model) {
        model.publishDate = moment(model.publishDate).toDate();
        model.isBundle = Number(model.isBundle) === 1;

        if (model.discountStartDate && model.discountEndDate) {
          this.period = formatIntervalForPicker(model.discountStartDate, model.discountEndDate);
        } else {
          this.period = [];
        }
      }

      this.model = model;
    },

    transformModel() {
      if (!this.image.imageSet) {
        throw new Error('Provide an image for the product!');
      }

      let {startDate, endDate} = (this.model.discount && this.period.length)
          ? formatIntervalForApi(this.period)
          : {
            startDate: null,
            endDate  : null
          };

      return _.extend({}, this.model, {
        image            : this.imageChanged ? this.image.generateDataUrl('image/jpeg', 0.8) : null,
        publishDate      : moment(this.model.publishDate).format('YYYY-MM-DD'),
        discountStartDate: startDate,
        discountEndDate  : endDate
      });
    },

    afterSave(model) {
      this.imageChanged = false;
      if (this.$route.params.product === 'add') {
        this.$router.push({
          name  : PRODUCT_NAME,
          params: {
            product: model.id
          }
        });
      }
    },

    onImageChanged() {
      this.imageChanged = true;
    },

    setActiveTab(tabIndex) {
      this.activeTab = tabIndex;
    },

    updateModel() {
      if (this.model.isBundle) {
        this.model.discountType = 'percent';
        if (this.model.id === undefined) {
          this.model.price = '0';
        }
      }
    },

    customLabel({title, price}) {
      if (typeof price == 'number' && !isNaN(price)) {
        // check if it is integer
        if (Number.isInteger(price)) {
          price = price / 100;
        }
      }

      return `${title} - $${price}`;
    }
  }
};
</script>
