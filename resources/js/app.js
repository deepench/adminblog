/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import common from './common.js'
Vue.mixin(common)
import VueProgressBar from 'vue-progressbar'
import moment from 'moment'
import router from './router'

import store from './store'
import { Form, HasError, AlertError } from 'vform';
import { slice } from 'lodash';
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use( CKEditor );


import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;
window.Fire =  new Vue();



window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

// Vue.filter('capitalize', function (text) {
//   return text.charAt(0).toUpperCase() + text.slice(1)
// })




Vue.component('mainapp', require('./components/mainapp.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('profile', require('./components/Profile.vue').default);
Vue.component('tag', require('./components/Tag.vue').default);
Vue.component('category', require('./components/Category.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('login', require('./components/login.vue').default);
Vue.component('role', require('./components/role.vue').default);
Vue.component('assignrole', require('./components/assignrole.vue').default);
 Vue.component('post', require('./components/createPost.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.filter('myDate',function(created){
    return moment(created).format('MMMM Do YYYY');
});

const app = new Vue({
  el: '#app',
  router,store,
    data() {
    return {
        search: ''
    }
  },
  methods: {
    searchIt: _.debounce(() => {
      console.log('searchit')
      Fire.$emit('searching');
    }, 1000)
  }
  
})