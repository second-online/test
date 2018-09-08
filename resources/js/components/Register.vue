<template>
	<div>
		<p v-if="errors.length">
			<ul>
				<li v-for="error in errors">{{ error }}</li>
			</ul>
		</p>

		<form v-on:submit.prevent="register">
			<input type="text" name="name" placeholder="name" v-model="name">
			<input type="text" name="email" placeholder="email" v-model="email">
			<input type="password" name="password" placeholder="password" v-model="password" min="6">
			<button type="submit">Register</button>
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
				errors: [],
				name: '',
				email: '',
				password: '',
				isLoading: false,
				redirectPath: 'broadcasts/3'
			}
		},
		methods: {
			register: function() {
				if (this.isLoading) { return; }

				this.errors = [];

				if (this.name.length == 0) {
					this.errors.push('Don\'t forget to add your name.')
				}

				if (this.password.length < 6) {
					this.errors.push('Password must be atleast 6 characters.')
				}

				if (this.errors.length > 0) {
					return;
				}

				axios
					.post('http://second.test/w/api/register', {
						name: this.name,
						email: this.email,
						password: this.password,
					})
					.then(response => {
						console.log(response);
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
				//this.redirectPath = data.fullPath;
				console.log(data);
			}
		}
	}
</script>