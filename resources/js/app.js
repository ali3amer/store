/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');



window.Vue = require('vue');

import {Form, HasError, AlertError} from 'vform';

Vue.component(HasError.name, HasError);

Vue.component(AlertError.name, AlertError);

window.Form = Form;

Vue.component('pagination', require('laravel-vue-pagination'));
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import swal from 'sweetalert2';

window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

import VueProgressBar from "vue-progressbar";

Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "3px"
});

const routes = [
    {path: '/categories', component: require('./components/Categories.vue').default},
    {path: '/clients', component: require('./components/Clients.vue').default},
    {path: '/expenses', component: require('./components/Expenses.vue').default},
    {path: '/orders', component: require('./components/Orders.vue').default},
    {path: '/products', component: require('./components/Products.vue').default},
    {path: '/reports', component: require('./components/Reports.vue').default},
    {path: '/users', component: require('./components/Users.vue').default},
    {path: '/settings', component: require('./components/Settings.vue').default},
    {path: '/control', component: require('./components/Control.vue').default},
    {path: '/count', component: require('./components/Count.vue').default},
    // { path: '/', component: require('./components/Master.vue').default,
    // children: [
    //     { path: 'users', component: require('./components/Users.vue').default }
    // ] }
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
