<template>
	<div>
	 	<h1>Register</h1>
	 	<p>{{ generalError }}</p>
<!-- 		<ul>
			<li v-for="error in errors">{{ error }}</li>
		</ul> -->

		<form v-on:submit.prevent="register">
			<input
				v-bind:class="{ error: errors.name }"
				v-model="name"
				type="text"
				placeholder="name"
			> {{ errors.name }}
			<input
				v-bind:class="{ error: errors.email }"
				v-model="email"
				type="text"
				placeholder="email"
			> {{ errors.email }}
			<input
				v-bind:class="{ error: errors.password }"
				v-model="password"
				type="password"
				placeholder="password"
			> {{ errors.password }}
			<button type="submit">Register</button>
		</form>
		<router-link to="register" :to="{ name: 'login', query: { redirect: redirectPath } }">
			I already have an account. Login
		</router-link>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				errors: {},
				name: '',
				email: '',
				password: '',
				isLoading: false,
				generalError: ''
			}
		},
		computed: {
			redirectPath: function() {
				return this.$route.query.redirect ? this.$route.query.redirect : '/';
			}
		},
		methods: {
			register: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

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
						// check for 422, 429
						const errorResponse = error.response.data.errors;
 
						if (errorResponse !== undefined) { 
							const errors = {};

							for (const property in errorResponse) {
								errors[property] = errorResponse[property][0];
							}

							this.errors = errors;
						}else {
							this.generalError = 'Something went wrong. Try again.';
						}
					})
					.then(() => {
						this.isLoading = false;
					});

				this.isLoading = true;
			},
			isFormValid: function() {
				let errors = {};
				let isFormValid = true;

				this.errors = {};

				if (this.name.length == 0) {
					errors['name'] = 'Enter your name.';
					isFormValid = false;
				}

				if (this.email.length == 0) {
					errors['email'] = 'Enter a valid email address';
					isFormValid = false;
				}

				if (this.password.length < 6) {
					errors['password'] = 'Password must be atleast 6 characters.';
					isFormValid = false;
				}

				this.errors = errors;

				return isFormValid;
			}
		}
	}
</script>

<style>
	.error {border: 1px solid red;}
</style>