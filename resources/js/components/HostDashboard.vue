<template>
	<div id="host-page">
		<host-chat/>
		<broadcast-chat

		/>
	</div>
</template>

<script>
	import HostChat from '../components/HostChat'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			HostChat,
			BroadcastChat
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get('http://second.test/w/api/host/dashboard')
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

			axios
				.get('http://second.test/w/api/host/dashboard')
				.then(response => {
					next(vm => vm.setData(response.data));
				})
				.catch(error => {
					(error.response.status === 401) ? next('login') : next('/');
				})
				.then(() => {
					// delete this?
				});
		},
		methods: {
			setData: function(data) {

			}
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