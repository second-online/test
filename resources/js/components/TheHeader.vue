<template>
	<header class="d-flex px-30 px-lg-60 flex-shrink-0 align-items-center">
		<div class="d-flex flex-grow-1">
			<router-link
				v-bind:to="{ name: 'home' }"
				class="logo line-height-1"
			>Second Online Campus</router-link>
		</div>
		<div class="d-flex flex-grow-1 justify-content-end justify-content-md-center">
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
					<ul class="px-30">
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'home' }">Home</router-link></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'schedule' }">Schedule</router-link></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link></li>
						<li @click="toggleMenu"><router-link v-bind:to="{ name: 'contact' }">Contact</router-link></li>
						<li><a href="https://pushpay.com/g/secondhouston?src=hpp" target="_blank">Give</a></li>
						<li>
							<span
								v-if="isUserAuthenticated"
								@click="logOut"
								class="clickable"
							>Logout</span>
							<router-link
								v-else
								:to="{ name: 'login', query: { redirect: $route.path } }"
							>Login</router-link>
						</li>
						<li
							v-if="isUserHost"
							@click="toggleMenu"
						><router-link v-bind:to="{ name: 'host' }">Host</router-link></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-none d-md-flex flex-grow-1 justify-content-end">
			<span
				v-if="isUserAuthenticated"
				class="user large font-weight-bold"
			>{{ user.name }}</span>
			<router-link
				v-else
				:to="{ name: 'login', query: { redirect: $route.path } }"
				class="large font-weight-bold"
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
				'isUserAuthenticated',
				'isUserHost'
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
					.post('/w/api/logout')
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