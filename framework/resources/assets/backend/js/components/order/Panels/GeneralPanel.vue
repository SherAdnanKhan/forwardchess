<template>
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title">General details</h2>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <TableRow label="Created at" :value="model.created_at"/>
                    <TableRow label="Updated at" :value="model.updated_at"/>
                    <TableRow label="Completed date" :value="model.completedDate"/>
                    <TableRow label="Paid date" :value="model.paidDate"/>
                    <TableRow label="Status" :value="model.status" class="info"/>
                    <TableRow label="Email" :value="model.email"/>
                    <TableRow label="Name" :value="model.fullName"/>
                    <TableRow label="Payment method" :value="model.paymentMethod"/>
                    <TableRow label="Currency" :value="model.currency"/>
                    <TableRow label="Subtotal" :value="model.subTotal" type="amount"/>
                    <TableRow label="Coupon" :value="model.coupon"/>
                    <TableRow label="Discount" :value="model.discount" type="amount"/>
                    <TableRow label="Tax rate amount" :value="model.taxRateAmount" type="percent"/>
                    <TableRow label="Tax amount" :value="model.taxAmount" type="amount"/>
                    <TableRow label="Total" :value="model.total" type="amount" class="primary"/>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

<script>
    import TableRow from '../../layout/TableRow';

    export default {
        components: {
            TableRow
        },

        props: {
            model: {
                type    : Object,
                required: true
            }
        }
    };
</script>
