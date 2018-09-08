import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/Home'
import Broadcast from './components/Broadcast'
import Sermons from './components/Sermons'
import Sermon from './components/Sermon'
import HostDashboard from './components/HostDashboard'
import Login from './components/Login'
import Register from './components/Register'
import NotFound from './components/404'

Vue.use(VueRouter)

export const router = new VueRouter({
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
			meta: { guest: true }
		},
		{
			path:'/register',
			name: 'register',
			component: Register,
			meta: { guest: true }
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