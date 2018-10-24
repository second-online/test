<template>
	<component
		:is="layout"
		:class="pageName"
	>
		<router-view ref="router"></router-view>
		<broadcast-popup
			v-if="showBroadcastPopup"
			:broadcast="broadcast"
		/>
	</component>
</template>

<script>
	import DefaultLayout from './layouts/DefaultLayout'
	import NoHeaderLayout from './layouts/NoheaderLayout'
	import HostLayout from './layouts/HostLayout'
	import BroadcastPopup from './components/BroadcastPopup'
	import { mapState } from 'vuex'

	export default {
		components: {
			DefaultLayout,
			NoHeaderLayout,
			HostLayout,
			BroadcastPopup
		},
		data: function() {
			return {
				showBroadcastPopup: false,
				broadcast: null
			}
		},
		computed: {
			...mapState([
				'layout',
			]),
			pageName: function() {
				return this.$route.name;
			}
		},
		methods: {
		    broadcastOpen: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast' 
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcastOpen(broadcast);
		    	} else {
		    		// this.broadcast = broadcast;
		    		// this.showBroadcastPopup = true;
		    	}
		    },
		    broadcastStarting: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast'
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcastInProgress(broadcast);
		    	} else {
		    		// this.broadcast = broadcast;
		    		// this.showBroadcastPopup = true;
		    	}
		    },
		    broadcastClosed: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast'
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcastClosed(broadcast);
		    	}
		    	// this.showBroadcastPopup = false;
		    	// this.broadcast = null;
		    }
		},
	    created: function() {
			Echo.channel('main')
				.listen('BroadcastOpen', data => {
					this.broadcastOpen(data);
				})
				.listen('BroadcastStarting', data => {
					this.broadcastStarting(data)
				})
				.listen('BroadcastClosed', data => {
					this.broadcastClosed(data);
				});
	    }
	}
</script>
