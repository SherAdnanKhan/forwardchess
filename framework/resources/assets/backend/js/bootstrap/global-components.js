import Vue from 'vue';
import Spinner from '../components/layout/Spinner.vue';
import UserName from '../components/layout/UserName.vue';
import Sidebar from '../components/layout/Sidebar.vue';
import Croppa from 'vue-croppa';
import DatePicker from 'vue2-datepicker';
import Flatpickr from 'vue-flatpickr-component';

Vue.component('Spinner', Spinner);
Vue.component('UserName', UserName);
Vue.component('Sidebar', Sidebar);
Vue.component('DatePicker', DatePicker);
Vue.component('Flatpickr', Flatpickr);

Vue.use(Croppa);