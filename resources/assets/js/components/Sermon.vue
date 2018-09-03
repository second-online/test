<template>
	<div>
		 
<!-- 			<youtube-player 
				v-if="showVideo"
				v-on:video-ended="videoEnded"
				video-id="{ sermon.youtube_id" }
			/> -->
	</div>
</template>

<script>
	//import YouTubePlayer from '../components/YoutubePlayer' 

	export default {
		components: {
			//YoutubePlayer
		},
		data: function() {
			return {
				sermonId: this.$route.params.sermon_id,
				showVideo: true
			}
		},
		methods: {
	        videoEnded: function() {
	            this.showVideo = false;
	        }
		},
// 		created: function() {
// console.log(this.$route.params.sermon_id);
// console.log(this.sermonId);
// console.log('created');
// 		},
		mounted: function() {

			console.log(this.sermonId);
			console.log(this.$route.params.sermon_id);


			let sermons = this.$store.state.sermons;

			let sermon = sermons.find(sermon => {
				return sermon.id == this.sermonId;
			});

			console.log(sermon.title);
		}
	}
</script>