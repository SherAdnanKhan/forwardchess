<template>
    <EditPage :isLoading="isLoading" :isEmpty="isEmpty">
        <div slot="main-content">
            <ApiError :error="error"/>

            <div v-if="!isEmpty">
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
                        <InputField label="Completed date" :value="model.completedDate" :readonly="true" icon="fa-calendar"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Paid date" :value="model.paidDate" :readonly="true" icon="fa-calendar"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Status" :value="model.status" :readonly="true"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Total" :value="model.total" :readonly="true" icon="fa-dollar"/>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Email" :value="model.email" :readonly="true" icon="fa-envelope"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Name" :value="model.fullName" :readonly="true"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Payment method" :value="model.paymentMethod" :readonly="true"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Currency" :value="model.currency" :readonly="true"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Sub total" :value="model.subTotal" :readonly="true" icon="fa-dollar"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Coupon" :value="model.coupon" :readonly="true"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Discount" :value="model.discount" :readonly="true"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <InputField label="Tax rate amount" :value="model.taxRateAmount" :readonly="true" icon="fa-dollar"/>
                    </div>
                    <div class="col-lg-6">
                        <InputField label="Tax amount" :value="model.taxAmount" :readonly="true" icon="fa-dollar"/>
                    </div>
                </div>
            </div>
        </div>
    </EditPage>
</template>

<script>
    import TabMixin from './TabMixin';

    export default {
        mixins: [
            TabMixin
        ],

        methods: {
            getResourceUrl(id) {
                return `orders/${id}`;
            }
        }
    };
</script>
