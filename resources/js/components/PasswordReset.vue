<template>
	<div>
	 	<h1>Request Password Reset Link</h1>
		<ul>
			<li v-for="alert in alerts">{{ alert }}</li>
		</ul>

		<form v-on:submit.prevent="register">
			<input
				v-bind:class="{ error: alerts.email }"
				v-model="email"
				type="text"
				placeholder="email"
			>
			<button type="submit">Reset Password</button>
		</form>
		<router-link v-bind:to="loginURL">
			Nvm, I remember my password.
		</router-link>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				alerts: {},
				email: '',
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
			loginURL: function() {
				return this.query ? '/login' + this.query : '/login';
			}
		},
		methods: {
			register: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }
 
				axios
					.post(process.env.MIX_APP_URL + '/w/api/password/email', {
						email: this.email,
					})
					.then(response => {
						console.log(response.data);
						this.setAlerts(response.data);
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

				this.setAlerts(newAlerts);

				return isFormValid;
			}
		}
	}
</script>

<style>
	.error {border: 1px solid red;}
</style>