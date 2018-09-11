<template>
	<div>
	 	<h1>Register</h1>
		<ul>
			<li v-for="alert in alerts">{{ alert }}</li>
		</ul>

		<form v-on:submit.prevent="register">
			<input
				v-bind:class="{ error: alerts.name }"
				v-model="name"
				type="text"
				placeholder="Full name"
			>
			<input
				v-bind:class="{ error: alerts.email }"
				v-model="email"
				type="text"
				placeholder="Email"
			>
			<input
				v-bind:class="{ error: alerts.password }"
				v-model="password"
				type="password"
				placeholder="Password"
			>
			<button type="submit">Register</button>
		</form>
		<!-- <router-link :to="{ name: 'login', query: { redirect: redirectPath } }"> -->
		<router-link v-bind:to="loginURL">
			I already have an account. Login
		</router-link>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				alerts: {},
				name: '',
				email: '',
				password: '',
				isLoading: false,
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
			loginURL: function() {
				return this.query ? '/login' + this.query : '/login';
			}
		},
		methods: {
			register: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

				axios
					.post(process.env.MIX_APP_URL + '/w/api/register', {
						name: this.name,
						email: this.email,
						password: this.password,
					})
					.then(response => {
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

				if (this.name.length == 0) {
					newAlerts.name = 'Enter your name.';
					isFormValid = false;
				}

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