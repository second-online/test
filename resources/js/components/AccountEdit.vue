<template>
	<div>
		<h2>Upload picture</h2>
		<form enctype="multipart/form-data">
		    Select picture to upload:
		    <input type="file" name="fileToUpload" id="fileToUpload" @change="fileChange">
		    <input @click="submit" type="submit" value="Upload picture" name="submit">
		</form>
		<button @click="deletePicture">Delete picture</button>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				picture: null
			}
		},
		methods: {
			fileChange: function(e) {
				this.picture = e.target.files[0];
			},
			submit: function(e) {

				const formData = new FormData();

				formData.append('_method', 'PATCH');
				formData.append('picture', this.picture);

				axios
					.post('/w/api/user/picture', formData, {
					    headers: {
					      'Content-Type': 'multipart/form-data'
					    }
					})
					.then(response => {
						console.log(response);
					})
					.catch(error => {
 
					});

				e.preventDefault();
			},
			deletePicture: function() {
				axios
					.post('/w/api/user/picture', {
						_method: 'DELETE'
					})
					.then(response => {
						console.log(response);
					})
					.catch(error => {
 
					});
			}
		}
	}
</script>