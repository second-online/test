<template>
	<main class="h-100">
		<router-view ref="master"></router-view>
		<div v-if="showVideo" class="popup-video"></div>
	</main>
</template>

<script>
	export default {
		data: function() {
			return {
				showVideo: false
			}
		},
		methods: {
		    broadcastOpen: function(broadcast) {
		    	// if (this.$refs.master.$options.name === 'host-dashboard') {
		    	// 	this.$refs.master.loadBroadcastChat(broadcast);
		    	// 	console.log(broadcast);
		    	// }
		    },
		    broadcastStarting: function(broadcast) {
		    	// if (this.$refs.master.$options.name === 'host-dashboard') {
		    	// 	this.$refs.master.loadBroadcastChat(broadcast);
		    	// }
		    },
		    broadcastClosed: function(broadcast) {
		    	// if (this.$refs.master.$options.name === 'host-dashboard') {
		    	// 	this.$refs.master.hideBroadcastChat(broadcast);
		    	// }
		    }
		},
	    mounted: function() {
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
