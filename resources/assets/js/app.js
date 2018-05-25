/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './views/App'
import Item from './views/Item';

Vue.use(VueRouter);

window.Vue = Vue;

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/items/:id',
            name: 'item',
            component: Item,
            props: true
        }
    ],
    base: '/destiny2/'
});

const app = new Vue({
    el: '#app',
    components: {App},
    router,
});
