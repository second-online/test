<template>
	<div class="home">
		Homepage {{ route }}
		<router-link v-bind:to="{ name: 'broadcast', params: { broadcast_id: 5} }">Goto Broadcast 5</router-link>
		<router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link>
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