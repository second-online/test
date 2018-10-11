<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">	 	 
		<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black justify-content-center video-content">
			<div class="d-flex px-30 px-md-60 align-items-center justify-content-center justify-content-md-start header video-header">
				<span
					v-on:click="goBack"
					class="close"
				>Close</span>
			</div>
			<vimeo-player
				v-if="videoLoaded"
				v-bind:video-id="sermon.vimeo_id"
				class="px-0 px-lg-60"
				ref="video"
			/> 
		</div>
		<video-sidebar>
			<div class="px-30 py-60">
				<h1>{{ sermon.title }}</h1>
				<div v-html="sermon.notes"></div>
			</div>
		</video-sidebar>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import VideoSidebar from '../components/VideoSidebar'

	export default {
		components: {
			VimeoPlayer,
			VideoSidebar
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