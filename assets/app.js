/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import Vue from 'vue'
import vuetify from './plugins/vuetify'
import VueRouter from 'vue-router'
import Question from "./components/Question/Question";
import Statistics from "./components/Statistics/Statistics";
const routes = [
    { path: '/', component: Question, name: 'home' },
    { path: '/statistics', component: Statistics, name: 'statistics' }
]

const router = new VueRouter({
    mode: 'history',
    base: '/app/',
    routes
})

Vue.use(VueRouter)

new Vue({
    router,
    vuetify
}).$mount('#app')

/*const app = new Vue({
    el: '#app', // where <div id="app"> in your DOM contains the Vue template
    data: () => ({
        questions: [],
        errors: []
    }),
    delimiters: ['${', '}$'],
    components: {
        Question
    }
});*/
