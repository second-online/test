<template>
	<header class="d-flex mx-30 mx-md-60 flex-shrink-0 align-items-center">
		<div class="flex-grow-1">
			<span class="logo">
				<router-link v-bind:to="{ name: 'home' }">Second Online Campus</router-link>
			</span>
		</div>
		<div class="d-flex flex-md-grow-1 justify-content-end justify-content-lg-center">
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
						<li>Schedule</li>
						<li>Contact</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="d-none d-lg-block flex-grow-1 text-right">
			<span
				v-if="isUserAuthenticated"
				class="xlarge font-weight-bold"
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
		    toggleMenu: function() {
		    	this.showMenu = !this.showMenu;
		    	this.$emit('menu-toggled', this.showMenu);
		    }
		}
	}
</script>