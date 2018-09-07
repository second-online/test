
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
import Sermons from './components/Sermons'
import Sermon from './components/Sermon'
import HostDashboard from './components/HostDashboard'
import Login from './components/Login'
import NotFound from './components/404'

import { store } from './store';

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'home',
			component: Home
		},
		{
			path: '/broadcasts/:broadcast_id',
			name: 'broadcast',
			component: Broadcast
		},
		{
			path: '/sermons',
			name: 'sermons',
			component: Sermons
		},
		{ 
			path: '/sermons/:sermon_id',
			name: 'sermon',
			component: Sermon
		},
		{
			path:'/host',
			name: 'host',
			component: HostDashboard,
		},
		{
			path:'/login',
			name: 'login',
			component: Login,
		},
		{
			path: '/404',
			name: '404',
			component: NotFound
		},  
		{
			path: '*',
			redirect: '/404'
		}
	] 
})

new Vue({
	el: '#app',
	store: store,
	router: router,
	render: h => h(App)
})
