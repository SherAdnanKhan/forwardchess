/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import firebase from 'firebase/app';
import 'firebase/auth';

import '../../common/bootstrap/validator';

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const firebaseConfig = {
  apiKey           : 'AIzaSyBIYARQzQ3ftqRTl27EwtkyeVyTK1snTXw',
  authDomain       : 'sms-validation-ada33.firebaseapp.com',
  databaseURL      : 'https://sms-validation-ada33.firebaseio.com',
  projectId        : 'sms-validation-ada33',
  storageBucket    : 'sms-validation-ada33.appspot.com',
  messagingSenderId: '724281353193',
  appId            : '1:724281353193:web:5c2acda33d9a7c8d6ee178'
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
