<template>
	<div class="d-flex px-30 flex-column flex-grow-1 align-items-center justify-content-center">
	 	<h1>Edit Profile</h1>
	 	
		<form enctype="multipart/form-data">
			<div class="d-flex mb-32 align-items-center justify-content-center picture-upload-wrapper">
				<img :src="user.profile_picture" class="profile-picture profile-picture-large">
				<div></div>
			    <input type="file" @change="uploadProfilePicture" ref="fileInput">
		    </div>
		</form>

		<form
			v-on:submit.prevent="updateUser"
			class="form-narrow"
		>
			<ul
				v-if="Object.keys(alerts).length"
				class="mb-10 list-unstyled text-left"
			>
				<li
					class="mb-0 py-0 text-danger"
					v-for="alert in alerts">{{ alert }}
				</li>
			</ul>
			<div class="form-group mb-10">
				<label for="name" class="sr-only font-weight-bold">Name</label>
				<input
					id="name"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.name ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="user.name"
					type="text"
					placeholder="Name"
				>
			</div>
			<div class="form-group mb-10">
				<label for="email" class="sr-only font-weight-bold">Email</label>
				<input
					id="email"
					class="form-control p-24 w-100 bg-light-grey"
					v-bind:class="[ alerts.email ? 'border border-1 border-danger' : 'border-0' ]"
					v-model="user.email"
					type="email"
					placeholder="Email"
				>
			</div>
			<button
				class="mb-32 py-24 w-100 border-0 text-white bg-black font-weight-bold" 
				type="submit"
			>Save</button>
		</form>
		<!-- <span @click="deleteProfilePicture">Delete picture</span> -->
	</div>
</template>

<script>
	import { mapState } from 'vuex'
	import { mapMutations } from 'vuex'

	export default {
		data: function() {
			return {
				alerts: {}
			}
		},
		computed: {
			...mapState([
				'user',
			]) 
		},
		methods: {
			...mapMutations([
				'setUserProfilePicture'
			]),
			uploadProfilePicture: function(e) {
				const picture = e.target.files[0];
				const formData = new FormData();

				formData.append('_method', 'PATCH');
				formData.append('picture', picture);

				axios
					.post('/w/api/user/picture', formData, {
					    headers: {
					      'Content-Type': 'multipart/form-data'
					    }
					})
					.then(response => {
						this.setUserProfilePicture(response.data.profile_picture);
					})
					.catch(error => {
 
					});

				this.$refs.fileInput.value = '';
			},
			deleteProfilePicture: function() {
				axios
					.post('/w/api/user/picture', {
						_method: 'DELETE'
					})
					.then(response => {
						this.setUserProfilePicture(response.data.profile_picture);
					})
					.catch(error => {
 
					});
			},
			updateUser: function() {
				axios
					.post('/w/api/user/edit', {
						_method: 'PATCH',
						name: this.user.name,
						email: this.user.email
					})
					.then(response => {
						console.log(response.data);
						//this.setUserProfilePicture(response.data.profile_picture);
					})
					.catch(error => {
 
					});
			}
		}
	}
</script>