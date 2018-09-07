<template>
	<div class="home">
		Homepage
		<router-link v-bind:to="{ name: 'broadcast', params: { broadcast_id: 3} }">Broadcast 5</router-link>
		<router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link>
		<router-link v-bind:to="{ name: 'host' }">Host</router-link>
	</div>
</template>

<script>
	export default {
		computed: {
			route: function() {
				return this.$route.params.username
			}
		}
	}
</script>