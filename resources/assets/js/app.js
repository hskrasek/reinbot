/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './views/App'
import Item from './views/Item';
import Activity from './views/Activity';
import Backhouse from './views/Backhouse';

Vue.use(VueRouter);

window.Vue = Vue;

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/destiny2/items/:id',
            name: 'item',
            component: Item,
            props: true
        },
        {
            path: '/destiny2/activities/:id',
            name: 'activity',
            component: Activity,
            props: true
        },
        {
            path: '/backhouse',
            name: 'backhouse',
            component: Backhouse,
            props: false
        }
    ],
    base: '/'
});

const app = new Vue({
    el: '#app',
    components: {App},
    router,
});
