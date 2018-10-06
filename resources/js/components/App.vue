<template>
	<main class="h-100">
		<router-view ref="router"></router-view>
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
		    	if ((this.$router.currentRoute.name == 'broadcast' 
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcast = broadcast;
		    		this.$refs.router.broadcastOpen();

		    		console.log('broadcast open');
		    	}
		    },
		    broadcastStarting: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast'
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcast = broadcast;
		    		this.$refs.router.broadcastInProgress();
		    		
		    		console.log('broadcast starting');
		    	}
		    },
		    broadcastClosed: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast'
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.broadcastClosed();
		    		
		    		console.log('broadcast closed');
		    	}
		    }
		},
	    mounted: function() {
			Echo.channel('main')
				.listen('BroadcastOpen', data => {
					this.broadcastOpen(data);
					console.log(data);
				})
				.listen('BroadcastStarting', data => {
					this.broadcastStarting(data)
					console.log(data);
				})
				.listen('BroadcastClosed', data => {
					this.broadcastClosed(data);
					console.log(data);
				});
	    }
	}
</script>
