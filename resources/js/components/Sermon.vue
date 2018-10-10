<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">	 	 
		<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black justify-content-center video-content">
			<div class="px-40 d-flex align-items-center header video-header">
				<span class="text-white clickable" v-on:click="goBack">Close</span>
			</div>
			<vimeo-player
				v-if="videoLoaded"
				v-bind:video-id="sermon.vimeo_id"
				ref="video"
			/> 
		</div>
		<div
			v-html="sermon.description"
			class="py-40 px-40 flex-grow-1 overflow-y bg-light-grey video-sidebar"
		></div>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'

	export default {
		components: {
			VimeoPlayer
		},
		data: function() {
			return {
				sermon: {},
				videoLoaded: false,
				previousPage: {}
			}
		},
		beforeRouteEnter (to, from, next) {
			next(vm => vm.setData(from));
		},
		methods: {
			setData: function(from) {
				this.previousPage = from;
			},
		    goBack () {
		    	if (this.previousPage.name === null) {
		    		this.$router.push({name: 'home'});
		    	} else {
		    		this.$router.go(-1);
		    	}
		    }
		},
		created: function() {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/sermons/' + this.$route.params.sermon_id)
				.then(response => {
					this.sermon = response.data;
					this.videoLoaded = true;
				})
				.catch(error => {
					console.log(error.response.status);
				})
				.then(() => {
					
				});
		}
	}
</script>