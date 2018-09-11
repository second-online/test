<template>
	<div>
		<h1>Reset Password</h1>
		<ul>
			<li v-for="alert in alerts">{{ alert }}</li>
		</ul>

		<form v-on:submit.prevent="resetPassword">
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
				placeholder="Password"
			>
			<input
				v-bind:class="{ error: alerts.password }"
				v-model="confirmPassword"
				type="password"
				placeholder="Confirm Password"
			>
			<button type="submit">Reset Password</button>
		</form>
	</div>
</template>
<script>
	export default {
		data: function() {
			return {
				alerts: {},
				email: '',
				password: '',
				confirmPassword: '',
				token: this.$route.params.token,
				isLoading: false
			}
		},
		methods: {
			resetPassword: function() {
				if (this.isLoading) { return; }

				if (! this.isFormValid()) { return; }

				axios
					.post(process.env.MIX_APP_URL + '/w/api/password/reset', {
						email: this.email,
						password: this.password,
						password_confirmation: this.confirmPassword,
						token: this.token
					})
					.then(response => {
						this.$store.state.user = response.data;
						this.$router.push('/');
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

				if (this.password != this.confirmPassword) {
					newAlerts.password = 'Passwords do not match.';
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