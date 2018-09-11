<template>
	<div>
		<span v-on:click="goBack">close</span>
		<youtube-player 
			v-if="showVideo"
			v-on:video-ended="videoEnded"
			v-bind:video-id="sermon.youtube_id"
		/>
	</div>
</template>

<script>
	import YoutubePlayer from '../components/YoutubePlayer' 

	export default {
		components: {
			YoutubePlayer
		},
		data: function() {
			return {
				sermonId: this.$route.params.sermon_id,
				sermon: {},
				showVideo: false
			}
		},
		methods: {
			fetchSermon: function() {
				axios
					.get(process.env.MIX_APP_URL + '/w/api/sermons/' + this.sermonId)
					.then(response => {
						this.sermon = response.data.sermon;
						this.showVideo = true;
						console.log(response.data.sermon);
					});
			},
	        videoEnded: function() {
	            this.showVideo = false;
	            console.log('video ended');
	        },
	        goBack: function() {
	        	this.$router.go(-1);
	        }
		},
		created: function() {

			let sermon = this.$store.getters.getSermonWithId(this.sermonId);

			if (typeof sermon === 'undefined') {
				this.fetchSermon();
				console.log('yes undefined');
			} else {
				this.sermon = sermon;
				this.showVideo = true;
				console.log('show video');
			}
		}
	}
</script>