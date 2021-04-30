/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });



window.Vue = require('vue').default;


import VueRouter from 'vue-router';
import router from './router';
Vue.use(VueRouter);


import Notifications from 'vue-notification';
Vue.use(Notifications);


import VueFormulate from '@braid/vue-formulate'
import { ru } from "@braid/vue-formulate-i18n";
const axiosInstance = axios.create({
  //baseURL: "http://127.0.0.1:8000/",
  headers: {
    "Content-Type": "application/json"
  },
  transformResponse: axios.defaults.transformResponse.concat(data => {
    return data.props;
  })
});
Vue.use(VueFormulate, {
  uploader: axiosInstance,
  uploadUrl: "/lk/api/quiz/file-upload",
  plugins: [ru],
  locale: "ru"
});


import { BBreadcrumb } from 'bootstrap-vue';
Vue.component('BBreadcrumb', BBreadcrumb);

import Common from './mixins/Common';


//
import httpClient from './httpClient'; // Доступен и во Vuex
//

//Vue.use(VModal, { dialog: true });
import store from './store/store';

import App from './components/App';

Vue.mixin(Common); // Глобальные миксины, доступны в каждом компоненте
const app = new Vue({
  'el': '#app',
  render : r => r(App),
  router,
  store,
  methods:{
    getOut() {
      this.$router.push({ name: 'notFound404url' });
    },
    notify: function(props){
      let defaultProps = {
        group:'default-top-center',
        type:'warn', // success /  warn / error / info
        // title: '',
        // text: '',
        // duration: 3000,
        speed: 500,
      };
      switch(props.type){
        case "success": defaultProps.duration = 10000; break;
        case "warn": defaultProps.duration = 10000; break;
        case "error": defaultProps.duration = 10000; break;
      }
      return this.$notify(Object.assign(defaultProps, props));
    },
    ajax_basic: function (DataToGo, url) {
      return httpClient(DataToGo, url)
        .then(response => {
          if (typeof response.data.auth.user !== "undefined") {
            this.$store.commit("setAuthUser", response.data.auth.user);
          }
          if (typeof response.data.auth.permissions !== "undefined") {
            this.$store.commit(
              "setAuthPermissions",
              response.data.auth.permissions
            );
          }
          return response;
        });
    },
    unique: function (arr) {
      var hash = {}, result = [];
      for ( var i = 0, l = arr.length; i < l; ++i ) {
        if ( !hash.hasOwnProperty(arr[i]) ) { //it works with objects! in FF, at least
          hash[ arr[i] ] = true;
          result.push(arr[i]);
        }
      }
      return result;
    }
  }
});
