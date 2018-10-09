<template>
	<div
		v-bind:class="{ 'overflow-hidden' : isMenuActive }"
		class="d-flex flex-column h-100"
	>
		<app-header
			v-on:menu-toggled="menuToggled"
			class="d-flex px-60 flex-shrink-0 align-items-center"
		/>
		<main
			class="d-flex flex-grow-1 mh-0"
			role="main"
			ref="main"
		>
			<router-view ref="router"></router-view>
		</main>
	</div>
</template>
<script>
	import AppHeader from '../components/AppHeader'

	export default {
		components: {
			AppHeader
		},
		data: function() {
			return {
				showVideo: false,
				isMenuActive: false
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
		    },
		    menuToggled: function(menuActive) {
		    	this.isMenuActive = menuActive
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
