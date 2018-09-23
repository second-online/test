<template>
	<div class="d-flex h-100 align-items-center justify-content-center">
		<div class="form-page text-center">
			<h1 class="text-center">Hello, again.</h1>
			<ul class="mb-0 p-0 list-style-none text-left">
				<li
					class="mb-0 py-0 text-danger"
					v-for="alert in alerts">{{ alert }}
				</li>
			</ul>

			<form v-on:submit.prevent="login">
				<input
					class="mb-10 p-24 w-100"
					v-bind:class="[ alerts.email ? 'border border-top-0 border-right-0 border-bottom-0 border-danger' : 'border-0' ]"
					v-model="email"
					type="text"
					placeholder="Email"
				>
				<input
					class="mb-20 p-24 w-100"
					v-bind:class="[ alerts.password ? 'border border-top-0 border-right-0 border-bottom-0 border-danger' : 'border-0' ]"
					v-model="password"
					type="password"
					placeholder="Password"
				>
				<router-link
					class="d-inline-block mb-20"
					v-bind:to="passwordResetURL">
						Forgot password?
				</router-link>
				<button
					class="mb-30 p-24 w-100 border-0 text-white bg-gray-900 font-weight-bold" 
					type="submit"
				>Sign in</button>
			</form>
<!-- 			<router-link v-bind:to="registerURL">
				Don't have an account? Register now
			</router-link> -->

		</div>
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