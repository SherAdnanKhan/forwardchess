<template>
  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <h2 class="panel-title">Billing details</h2>
    </header>
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table">
          <tbody>
          <TableRow label="Created at" :value="model.created_at"/>
          <TableRow label="Updated at" :value="model.updated_at"/>
          <TableRow label="First name" :value="model.firstName"/>
          <TableRow label="Last name" :value="model.lastName"/>
          <TableRow label="Email" :value="model.email"/>
          <TableRow label="Phone" :value="model.phone"/>
          <TableRow label="Company" :value="model.company"/>
          <TableRow label="City" :value="model.city"/>
          <TableRow label="Post code" :value="model.postcode"/>
          <TableRow label="Address1" :value="model.address1"/>
          <TableRow label="Address2" :value="model.address2"/>
          <TableRow label="Country" :value="model.country"/>
          <TableRow label="State" :value="model.state"/>
          <tr v-if="location" class="table-row">
            <td class="col-label">
              Location
            </td>
            <td class="col-value">
              <table class="table">
                <tbody>
                <tr v-for="(value, name) in location" :key="name">
                  <td class="col-label" style="width: 100px;">
                    {{name}}
                  </td>
                  <td class="col-value">
                    {{value}}
                  </td>
                </tr>
                </tbody>
              </table>

            </td>
          </tr>
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
  },

  computed: {
    location() {
      const {location} = this.model;

      return location
          ? {
            IP       : location.ip,
            Country  : location.countryName,
            Region   : location.regionName,
            City     : location.city,
            Zip      : location.zip
          }
          : null
    }
  }
};
</script>
