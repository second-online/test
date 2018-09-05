<template>
	<router-view></router-view>
</template>

<script>
	export default {
	    mounted: function() {
	        // move this to seperate file?
	        window.onYouTubeIframeAPIReady = () => {
	            this.$store.state.youtubeApiReady = true; 
	            console.log('api ready');         
	        }
	    }
	}
</script>
