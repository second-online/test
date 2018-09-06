<template>
	<div id="host-page">
		<host-chat></host-chat>
	</div>
</template>

<script>
	import HostChat from '../components/HostChat'

	export default {
		components: {
			HostChat
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get('http://second.test/w/api/host/authorize')
				.then(response => {
					// if response successful continue on
					console.log('test');
					next();
				})
				.catch(error => {
					(error.response.status === 401) ? next('login') : next('/');
				})
				.then(function () {
					// always executed

				});
		},
		created: function() {
			console.log('host dashboard created');
			axios
				.get('http://second.test/w/api/host/dashboard')
				.then(response => {
					
				})
				.catch(error => {
					console.log(error);
				})
				.then(function () {

				});
		},
		mounted: function() {
			console.log('host dashboard mounted');
		}
	}
</script>