import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		user: window.AppUser,
		layout: null
	},
	getters: {
		isUserAuthenticated: state => {
			return state.user !== null; 
		},
		isUserHost: state => {
			return state.user !== null && state.user.is_host === true;
		}
	},
	mutations: {
		setUser (state, user) {
			state.user = user;
		},
		setLayout (state, layout = 'default-layout') {
			state.layout = layout;
		}
	},
	actions: {
		logUserOut (context) {
			context.commit('setUser', null);
			window.AppUser = null;
		}
	}
});