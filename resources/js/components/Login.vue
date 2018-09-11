<template>
	<div>
		<h1>Login</h1>
		<ul>
			<li v-for="alert in alerts">{{ alert }}</li>
		</ul>

		<form v-on:submit.prevent="login">
			<input
				v-bind:class="{ error: alerts.email }"
				v-model="email"
				type="text"
				placeholder="email"
			>
			<input
				v-bind:class="{ error: alerts.password }"
				v-model="password"
				type="password"
				placeholder="password"
			>
			<button type="submit">Login</button>
		</form>
		<router-link v-bind:to="registerURL">
			Don't have an account? Register now
		</router-link>
		<router-link v-bind:to="passwordResetURL">
			Forgot password?
		</router-link>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				alerts: {},
				email: '',
				password: '',
				isLoading: false
			}
		},
		computed: {
			redirectPath: function() {
				return (typeof this.$route.query.redirect !== 'undefined')
					? this.$route.query.redirect
					: '/';
			},
			query: function() {
				return this.redirectPath != '/' ? '?redirect=' + this.redirectPath : null;
			},
			registerURL: function() {
				return this.query ? '/register' + this.query : '/register';
			},
			passwordResetURL: function() {
				return this.query ? '/password/reset' + this.query : '/password/reset';
			}
		},
		methods: {
			login: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

				axios
					.post(process.env.MIX_APP_URL + '/w/api/login', {
						email: this.email,
						password: this.password,
					})
					.then(response => {
						console.log(response.data);
						this.$store.state.user = response.data
						this.$router.push(this.redirectPath);
					})
					.catch(error => {
						if (error.response.data.errors != undefined) {
							this.setAlerts(error.response.data.errors);
						} else {
							this.setAlerts({error: 'Something went wrong. Try again.'});
						}
					})
					.then(() => {
						this.isLoading = false;
					});

				this.isLoading = true;
			},
			setAlerts: function(alerts) {
				const newAlerts = {};

				for (const property in alerts) {
					if (typeof alerts[property] === 'object') {
						newAlerts[property] = alerts[property][0];
					} else {
						newAlerts[property] = alerts[property];
					}
				}

				this.alerts = newAlerts;
			},
			isFormValid: function() {
				const newAlerts = {};
				let isFormValid = true;

				if (this.email.length == 0) {
					newAlerts.email = 'Enter a valid email address.';
					isFormValid = false;
				}

				if (this.password.length < 6) {
					newAlerts.password = 'Password must be at least 6 characters.';
					isFormValid = false;
				}

				this.setAlerts(newAlerts);

				return isFormValid;
			}
		}
	}
</script>

<style>
	.error {border: 1px solid red;}
</style>