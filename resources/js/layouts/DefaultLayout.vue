<template>
	<div
		v-bind:class="{ 'overflow-hidden' : isMenuActive }"
		class="d-flex flex-column h-100"
	>
		<app-header
			v-on:menu-toggled="menuToggled"
			class="d-flex px-60 flex-shrink-0 align-items-center"
		/>
		<main class="d-flex flex-grow-1 mh-0" role="main">
			<slot/>
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
				isMenuActive: false,
			}
		},
		methods: {
		    menuToggled: function(menuActive) {
		    	this.isMenuActive = menuActive
		    }
		},
		destroyed: function() {
			console.log('default layout destroyed');
		}
	}
</script>