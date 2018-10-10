import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state : {
		user: window.AppUser
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

	}
});
