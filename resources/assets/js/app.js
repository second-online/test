
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './components/App'
import Home from './components/Home'
import Broadcast from './components/Broadcast'
import NotFound from './components/404'

Vue.use(VueRouter)

/**
* Next, we will create a fresh Vue application instance and attach it to
* the page. Then, you may begin adding components to this application
* or customize the JavaScript scaffolding to fit your unique needs.
*/

// Vue.component('broadcast-comment', require('./components/BroadcastComment.vue'));

// Vue.component('sermons', require(''))

// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.

// 1. Define route components.
// These can be imported from other files
// const Home = { template: '<div>foo</div>' }
// const Broadcast = { template: '<div>bar</div>' }
const User = { template: '<div>{{ $route.params.id }}</div>' }

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.


// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			component: Home
		},
		{
			path: '/user/:id',
			component: User
		},
		{
			path: '/broadcasts/:id',
			component: Broadcast
		},
		{
			path: '*',
			component: NotFound
		},
	] 
})

const app = new Vue({
	el: '#app',
	components: {
		App
	},
	router,
})
