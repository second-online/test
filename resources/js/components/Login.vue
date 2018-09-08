<template>
	<div>
		<form v-on:submit.prevent="login">
			<input type="text" name="email" placeholder="email" v-model="email">
			<input type="text" name="password" placeholder="password" v-model="password">
			<button type="submit">Login</button>
		</form>
	</div>
</template>

<script>
	export default {
		beforeRouteEnter (to, from, next) {
			next(vm => vm.setData(from));
		},
		data: function() {
			return {
				email: '',
				password: '',
				isLoading: false,
				redirectPath: ''
			}
		},
		methods: {
			login: function() {
				if (this.isLoading) { return; }

				axios
					.post('http://second.test/w/api/login', {
						email: this.email,
						password: this.password,
					})
					.then(response => {
						//console.log(response);
						this.$store.state.user = response.data
						this.$router.push(this.redirectPath);
					})
					.catch(error => {
						console.log('catch');
					})
					.then(() => {
						this.isLoading = false;
					});

				this.isLoading = true;
			},
			setData: function(data) {
				this.redirectPath = data.fullPath;
				console.log(this.redirectPath);
			}
		}
	}
</script>