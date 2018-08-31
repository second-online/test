<template>
	<div class="youtube">
		<div id="player"></div>
	</div>
</template>

<script>
	export default {
		props: ['videoId'],
		data: function() {
			return {
				videoTitle: 'What in the money',
				duration: '',
			}
		},
		methods: {
			videoFinished: function(event) {
				console.log('This video finished. Do something man!');
			}
		},
		mounted: function() {

			console.log('video id ' + this.videoId);

			var tag = document.createElement('script');

			tag.src = "http://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
 
			var player;
			window.onYouTubeIframeAPIReady = () => {
			 
				player = new YT.Player('player', {
					height: '390',
					width: '640',
					videoId: this.videoId, // I need to put ID from props here
					events: {
						'onReady': window.onPlayerReady,
						'onStateChange': window.onPlayerStateChange
					}
				});
			}

			window.onPlayerReady = (event) => {
				console.log('onPlayerReady');
				event.target.playVideo();
			}

			window.onPlayerStateChange = (event) => {
				this.videoFinished(event);
				// when video ends I need to emit notification to parent
			}
		

		}
	}	
</script>