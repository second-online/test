<template>
	<div>
		Contact page.. working on this..
		<form @submit.prevent="submit">
			<input type="email" name="email" placeholder="Email" v-model="email">
			<input type="text" name="name" placeholder="Name" v-model="name">
			<textarea placeholder="Message" v-model="message"></textarea>
			<input type="submit" name="">
		</form>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				email: null,
				name: null,
				message: null
			}
		},
		methods: {
			submit: function() {
				axios
					.post('/w/api/contact', {
						email: this.email,
						name: this.name,
						message: this.message
					})
					.then(response => {
console.log(response.data);
					})
					.catch(error => {

					})
					.then(() => {

					});
			}
		}
	}
</script>