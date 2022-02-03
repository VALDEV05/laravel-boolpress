/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/* SETUP VUE-Router */
//Installa Vue Router - Step 0 
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// 1. Define route pages components

const Home = Vue.component('Home', require('./pages/Home.vue').default);
const About = Vue.component('About', require('./pages/About.vue').default);
const Contacts = Vue.component('Contacts', require('./pages/Contacts.vue').default);

// 2. Define some routes
const routes = [{
            path: '/', //URI
            name: 'home', //name della rotta
            component: 'Home' //componente da restituire '''''view'''''
        },
        {
            path: '/about', //URI
            name: 'about', //name della rotta
            component: 'About' //componente da restituire '''''view'''''
        },
        {
            path: '/contacts', //URI
            name: 'contacts', //name della rotta
            component: 'Contacts' //componente da restituire '''''view'''''
        }
    ]
    // 3. Create the router instance and pass the `routes` option
const router = new VueRouter({
    routes // short for `routes: routes`
})



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

Vue.component('posts-list', require('./components/PostsListComponent.vue').default);


// 4. Create and mount the root instance.

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
});