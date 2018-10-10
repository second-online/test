import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/Home'
import Broadcast from './components/Broadcast'
import Sermons from './components/Sermons'
import Sermon from './components/Sermon'
import Schedule from './components/Schedule'
import HostDashboard from './components/HostDashboard'
import Login from './components/Login'
import Register from './components/Register'
import PasswordReset from './components/PasswordReset'
import PasswordResetChange from './components/PasswordResetChange'
import Admin from './components/Admin'
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
			component: Broadcast,
			meta: { layout: 'no-header-layout' }
		},
		{
			path: '/sermons',
			name: 'sermons',
			component: Sermons
		},
		{ 
			path: '/sermons/:sermon_id',
			name: 'sermon',
			component: Sermon,
			meta: { layout: 'no-header-layout' }
		},
		{
			path: '/schedule',
			name: 'schedule',
			component: Schedule 
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
			path:'/password/reset',
			name: 'password.reset',
			component: PasswordReset,
			meta: { guest: true }
		},
		{
			path:'/password/reset/:token',
			name: 'password.reset.change',
			component: PasswordResetChange,
			meta: { guest: true }
		},
		{
			path: '/404',
			name: '404',
			component: NotFound
		},  
		{
			path: '/admin',
			name: 'admin',
			component: Admin
		},
		{
			path: '*',
			redirect: '/404'
		}
	] 
})