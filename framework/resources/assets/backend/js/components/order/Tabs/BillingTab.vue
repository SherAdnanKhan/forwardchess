<template>
    <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
        <div slot="main-content">
            <ApiError :error="error"/>

            <form id="formModel" role="form" method="post" @submit.prevent="save" autocomplete="off" v-if="!isEmpty">
                <div class="row">
                    <div class="col-lg-6">
                        <InputField
                                label="First name"
                                name="firstName"
                                icon="fa-pencil-square-o"
                                v-model="model.firstName"
                                :errorMsg="errors.has('firstName') ? errors.first('firstName') : ''"
                                v-validate="'required'"
                                data-vv-as="first name"
                                :tabindex="1"
                        />
                    </div>
                    <div class="col-lg-6">
                        <InputField
                                label="Last name"
                                name="lastName"
                                icon="fa-pencil-square-o"
                                v-model="model.lastName"
                                :errorMsg="errors.has('lastName') ? errors.first('lastName') : ''"
                                v-validate="'required'"
                                data-vv-as="last name"
                                :tabindex="2"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField
                                label="Email"
                                name="email"
                                icon="fa-envelope"
                                v-model="model.email"
                                :errorMsg="errors.has('email') ? errors.first('email') : ''"
                                v-validate="'required'"
                                :tabindex="3"
                        />
                    </div>
                    <div class="col-lg-6">
                        <InputField
                                label="Phone"
                                name="phone"
                                icon="fa-phone"
                                v-model="model.phone"
                                :tabindex="4"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField
                                label="Company"
                                name="company"
                                v-model="model.company"
                                :tabindex="6"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField
                                label="City"
                                name="city"
                                v-model="model.city"
                                :tabindex="7"
                        />
                    </div>

                    <div class="col-lg-6">
                        <InputField
                                label="Post code"
                                name="postcode"
                                v-model="model.postcode"
                                :tabindex="8"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField
                                label="Address1"
                                name="address1"
                                v-model="model.address1"
                                :tabindex="9"
                        />
                    </div>

                    <div class="col-lg-6">
                        <InputField
                                label="Address2"
                                name="address2"
                                v-model="model.address2"
                                :tabindex="10"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group" :class="{'has-errors': errors.has('country')}">
                            <label>Country</label>
                            <CustomSelect
                                    v-model="model.country"
                                    name="country"
                                    valueProperty="code"
                                    :options="countries"
                                    data-vv-value-path="selected"
                                    data-vv-validate-on="input"
                                    v-validate="'required'"
                                    :tabindex="11"
                            />
                            <span v-show="errors.has('country')" class="error-msg">{{ errors.first('country') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>State</label>
                            <CustomSelect
                                    name="state"
                                    valueProperty="code"
                                    :options="states"
                                    v-model="model.state"
                                    :tabindex="12"
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt-20">
                        <SaveButton :tabindex="13"/>
                    </div>
                </div>
            </form>
        </div>
    </EditPage>
</template>

<script>
    import TabMixin from './TabMixin';
    import Countries from '../../mixins/Countries';

    export default {
        mixins: [
            TabMixin,
            Countries
        ],

        watch: {
            'model.country': {
                immediate: true,
                handler  : async function (countryCode) {
                    this.getStates(countryCode);
                }
            }
        },

        methods: {
            getResourceUrl(id) {
                return `orders/${id}/billing`;
            },

            async save() {
                await this.$validator.validateAll();
            }
        }
    };
</script>
