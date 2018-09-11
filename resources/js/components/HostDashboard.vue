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
				.get(process.env.MIX_APP_URL + '/w/api/host/dashboard')
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
		},
		mounted: function() {
			console.log('host dashboard mounted');
		}
	}
</script>