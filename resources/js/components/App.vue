<template>
	<div
		v-bind:class="{ 'overflow-hidden' : showMenu }"
		class="d-flex flex-column h-100"
	>
		<header
			v-if="showHeader"
			class="d-flex px-60 flex-shrink-0 align-items-center"
		>
			<div class="flex-grow-1">
				<span class="logo">Second Online Campus</span>
			</div>
			<div class="d-flex flex-grow-1 justify-content-end justify-content-lg-end">
				<span
					v-on:click="toggleMenu"
					v-bind:class="{ activated: showMenu }"
					class="menu-toggle"
				></span>
				<div
					v-show="showMenu"
					class="menu"
				>
					<ul class="list-unstyled text-center huge">
						<li><router-link v-bind:to="{ name: 'home' }">Home</router-link></li>
						<li><router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link></li>
						<li>Schedule</li>
						<li>Contact</li>
					</ul>
				</div>
			</div>
<!-- 			<div class="flex-grow-1 text-center">
				<ul class="m-0 list-inline font-weight-bold">
					<li class="d-inline-block px-10"><router-link v-bind:to="{ name: 'home' }">Home</router-link></li>
					<li class="d-inline-block px-20"><router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link></li>
					<li class="d-inline-block px-20">Schedule</li>
					<li class="d-inline-block px-10">Contact</li>
				</ul>
			</div> -->
			<div class="d-none flex-grow-1 text-right">
				<span
					v-if="isUserAuthenticated"
					class="xlarge font-weight-bold"
				>{{ user.name }}</span>
				<span
					v-else
					v-on:click="login"
					class="xlarge font-weight-bold"
				>Login</span>
			</div>
		</header>
		<main
			class="d-flex flex-grow-1 bg-warning mh-0"
			role="main"
			ref="main"
		>
			<router-view ref="router"></router-view>
		</main>
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'

	export default {
		data: function() {
			return {
				showVideo: false,
				showMenu: false
			}
		},
		computed: {
			...mapState([
				'user',
				'showHeader'
			]),
			...mapGetters([
				'isUserAuthenticated'
			])
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
		    toggleMenu: function() {
		    	this.showMenu = this.showMenu ? false : true;
		    	console.log(this.$refs.main);
		    },
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			},
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
