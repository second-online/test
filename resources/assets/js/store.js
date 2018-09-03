import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state : {
		user: {},
		schedule: [],
		sermons: [],
		youtubeApiReady: false
	},
	getters: {
		youtubeApiReadyStatus(state) {
			return state.youtubeApiReady;
		}
	}
});
