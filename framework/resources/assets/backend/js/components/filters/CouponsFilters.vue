<template>
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title display-inline">Filters</h2>
        </header>
        <div class="panel-body">
            <!-- <div class="col-lg-3">
                 <div class="form-group">
                     <label>Publisher</label>
                     <multiselect v-model="filters.publisher"
                                  :options="publishers"
                                  :searchable="true"
                                  :show-labels="false"
                                  label="name"
                                  track-by="id"
                                  placeholder="Pick a publisher"
                                  :tabindex="1">
                     </multiselect>
                 </div>
             </div>
             <div class="col-lg-3">
                 <div class="form-group">
                     <label>Category</label>
                     <multiselect v-model="filters.category"
                                  :options="categories"
                                  :searchable="true"
                                  :show-labels="false"
                                  label="name"
                                  track-by="id"
                                  placeholder="Pick a category"
                                  :tabindex="1">
                     </multiselect>
                 </div>
             </div>-->
        </div>
    </section>
</template>

<script>
    // import _ from 'lodash';
    import Filters from '../mixins/Filters';

    export default {
        mixins: [
            Filters
        ]
    };
</script>
