import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state : {
		user: { name: 'Alex Lacayo'},
		schedule: [],
		sermons: [],
		youtubeApiReady: false
	},
	getters: {
		getSermonWithId: (state) => (id) => {
			return state.sermons.find(sermon => sermon.id == id);	
		}
	}
});
