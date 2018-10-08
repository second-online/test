<template>
	<div class="p-30 w-75">
		<editor api-key="pqneqwzrqthtizen3374n6dimsmrieuw61j5wjleiugxtsy1" :init="options" v-model="content"></editor>
		
		<button v-on:click="submit">Submit</button>
	</div>
</template>

<script>
	import Editor from '@tinymce/tinymce-vue';

	export default {
		components: {
			Editor
		},
		data: function() {
			return {
				content: '',
				options: {
				    menubar:false,
    				statusbar: false,
					plugins: 'lists',
					toolbar: 'formatselect,bold,italic,underline,removeformat'
				}

			}
		},
		methods: {
			submit: function() {
				console.log(this.content);

				axios
					.post(process.env.MIX_APP_URL + '/w/api/admin/sermons/1', {
						_method: 'PUT',
						description: this.content
					})
					.then(response => {
 					
					})
					.catch(error => {

					});
			}
		}
	}

</script>