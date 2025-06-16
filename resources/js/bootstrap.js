import axios from 'axios';
import $ from 'jquery';
import 'bootstrap';
import 'simplebar';
import Cookies from 'js-cookie';
import 'popper.js';
import 'jquery.appear';
import 'jquery-scroll-lock';
import lodash from 'lodash';
import { createApp } from 'vue'; // Correct way to import Vue in Vue 3
import App from './App.vue'; // Make sure this is the path to your main Vue component

// Global window variables (if needed)
window.$ = $;
window.jQuery = $;
window.Cookies = Cookies;
window._ = lodash;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Create and mount the Vue app
const app = createApp(App); // Correct Vue 3 initialization
app.mount('#page-container'); // Make sure this selector exists in your HTML
