<template>
	<header v-if="showHeader">
		<div class="flex-grow-1">
			<span class="logo">
				<router-link v-bind:to="{ name: 'home' }">Second Online Campus</router-link>
			</span>
		</div>
		<div class="d-flex flex-grow-1 justify-content-end justify-content-lg-center">
			<span
				v-on:click="toggleMenu"
				v-bind:class="{ activated: showMenu }"
				class="menu-toggle"
			></span>
			<div
				v-bind:class="{ activated: showMenu }"
				class="menu overflow-hidden"
			>
				<ul class="list-unstyled text-center huge">
					<li v-on:click="toggleMenu"><router-link v-bind:to="{ name: 'home' }">Home</router-link></li>
					<li v-on:click="toggleMenu"><router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link></li>
					<li>Schedule</li>
					<li>Contact</li>
				</ul>
			</div>
		</div>
		<div class="d-none d-lg-block flex-grow-1 text-right">
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
</template>

<script>
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'

	export default {
		data: function() {
			return {
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
		    toggleMenu: function() {
		    	console.log('click');
		    	this.showMenu = !this.showMenu;
		    	this.$emit('menu-toggled', this.showMenu);
		    },
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			}
		}
	}
</script>