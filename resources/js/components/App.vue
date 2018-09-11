<template>
	<div style="height: 100%;">
		<router-view></router-view>
		<div v-if="showVideo" class="popup-video"></div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				showVideo: false
			}
		},
		methods: {

		},
	    mounted: function() {
	        // move this to seperate file?
	        window.onYouTubeIframeAPIReady = () => {
	            this.$store.state.youtubeApiReady = true;       
	        }

			Echo.channel('main')
				.listen('BroadcastStarting', data => {
					console.log(data);
			});
	    }
	}
</script>
