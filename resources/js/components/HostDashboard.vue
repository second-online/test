<template>
	<div>
		<p>Host Panel</p>
	</div>
</template>

<script>
	export default {
		beforeRouteEnter (to, from, next) {
			axios
				.get('http://second.test/w/api/host/dashboard')
				.then(response => {
					// handle success
					//console.log(response);
				})
				.catch(error => {
					// handle error
					console.log(error.response);
					// not logged in = 401 / Unauthorized
					// invalid role = 403 / Forbidden
				})
				.then(function () {
					// always executed
					console.log('always executed');
				});
		}
	}
</script>