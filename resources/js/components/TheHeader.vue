<template>
	<header class="d-flex px-30 px-md-60 flex-shrink-0 align-items-center">
		<div class="flex-grow-1">
			<router-link
				v-bind:to="{ name: 'home' }"
				class="logo"
			>Second Online Campus</router-link>
		</div>
		<!-- justify-content-lg-center -->
		<div class="d-flex flex-md-grow-1 justify-content-end">
			<span
				@click="toggleMenu"
				v-bind:class="{ activated: showMenu }"
				class="menu-toggle"
			></span>
			<div
				v-bind:class="{ activated: showMenu }"
				class="menu overflow-hidden"
			>	
				<div class="h-100 overflow-y">
					<ul>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'home' }">Home</router-link></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'schedule' }">Schedule</router-link></li>
						<li><a href="https://pushpay.com/g/secondhouston?src=hpp" target="_blank">Give</a></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'contact' }">Contact</router-link></li>
						<li
							v-if="isUserAuthenticated"
							@click="toggleMenu"
						><span
							@click="logOut"
							class="clickable"
						>Logout</span></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'host' }">Host</router-link></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- dg-lg-none -->
		<div class="d-none flex-grow-1 text-right">
			<span
				v-if="isUserAuthenticated"
				class="user xlarge font-weight-bold"
			>{{ user.name }}</span>
			<router-link
				v-else
				v-bind:to="{ name: 'login', query: { redirect: $route.path } }"
				class="xlarge font-weight-bold"
			>Login</router-link>
		</div>
	</header>
</template>

<script>
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'
	import { mapActions } from 'vuex'

	export default {
		data: function() {
			return {
				showMenu: false
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			...mapGetters([
				'isUserAuthenticated'
			])
		},
		methods: {
			...mapActions([
				'logUserOut'
			]),
		    toggleMenu: function() {
		    	this.showMenu = !this.showMenu;
		    	this.$emit('menu-toggled', this.showMenu);
		    },
		    logOut: function() {
				axios
					.post(process.env.MIX_APP_URL + '/w/api/logout')
					.then(response => {
						console.log(response.data)
					})
					.catch(error => {
						console.log(error.response.status);
					})
					.then(() => {
						this.logUserOut();
						this.$router.push('/');
					});	
		    }
		}
	}
</script>