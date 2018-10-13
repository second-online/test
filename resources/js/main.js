
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

import Vue from 'vue'
import App from './App'

import { store } from './store'
import { router } from './routes'

router.beforeEach((to, from, next) => {
	const layoutRecord = to.matched.find(record => record.meta.layout);

	if (layoutRecord !== undefined) {
		store.commit('setLayout', layoutRecord.meta.layout);
	} else {
		store.commit('setLayout');
	}

	if (to.matched.some(record => record.meta.guest)) {
		if (store.getters.isUserAuthenticated) {
			next('/');
		} else {
			next();
		}
	} else {
		next();
	}
})

new Vue({
	el: '#app',
	store: store,
	router: router,
	render: h => h(App)
})
