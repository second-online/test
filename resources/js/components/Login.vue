<template>
	<div class="d-flex flex-column h-100 align-items-center justify-content-center">
		<h1 class="mb-48 text-center">Sign in.</h1>
		<form class="form-narrow" v-on:submit.prevent="login">
			<ul class="mb-0 list-unstyled text-left">
				<li
					class="mb-0 py-0 text-danger"
					v-for="alert in alerts">{{ alert }}
				</li>
			</ul>
			<div class="form-group mb-12">
				<label for="email" class="sr-only font-weight-bold">Email</label>
				<input
					id="email"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.email ? 'border border-top-0 border-right-0 border-bottom-0 border-danger border-3' : 'border-0' ]"
					v-model="email"
					type="email"
					placeholder="Email"
				>
			</div>
			<div class="form-group mb-12"> 
				<label for="password" class="sr-only font-weight-bold">Password</label>
				<div class="d-flex align-items-center bg-light-grey">
					<input
						id="password"
						class="form-control p-24 flex-grow-1 bg-light-grey"
						v-bind:class="[ alerts.password ? 'border border-top-0 border-right-0 border-bottom-0 border-danger border-3' : 'border-0' ]"
						v-model="password"
						type="password"
						placeholder="Password"
					>
					<router-link
						class="mx-24 font-weight-bold"
						v-bind:to="passwordResetURL">
							Reset
					</router-link>
				</div>
			</div>
			<button
				class="mb-48 py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Sign in</button>

<!-- 				<div class="d-flex justify-content-center font-weight-bold">
				<span class="pr-20">Sign up</span>
				<span class="pl-20">Forgot password</span>
			</div> -->

			<span class="d-block text-center">
				<span>Dont have an account?</span>
				<router-link
					class="ml-4 font-weight-bold"
					v-bind:to="registerURL">
					Sign up
				</router-link>
			</span>
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