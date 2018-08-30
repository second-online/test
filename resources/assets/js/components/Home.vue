<template>
	<div class="home">
		Homepage {{ route }}
		<router-link to="/broadcasts/5">Goto Broadcast 5</router-link>
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