<template>
	<div id="vimeo-player" class="embed-responsive embed-responsive-16by9 broadcast-video"></div>
</template>

<script>
	export default {
		props: {
			videoId: String,
			timeElapsed: {
				type: Number,
				default: 0
			}
		},
		data: function() {
			return {
				player: {}
			}
		},
		watch: {
			videoId: function() {
				this.loadNewVideo();
			}
		},
		methods: {
			loadVideo: function() {
			    var options = {
			        id: this.videoId
			    };

			    this.player = new Vimeo('vimeo-player', options);
				this.player.setVolume(0);	
				this.player.setCurrentTime(this.timeElapsed);
				// this.player.ready().then(() => {
				// 	this.play();
				// });
			},
			loadNewVideo: function() {
				this.player.destroy().then(() => {
				    this.loadVideo();
				}).catch(function(error) {
				    alert('Something went wrong. Reload the page.');
				});

			},
			play: function() {
				this.player.play().then(() => {
					console.log('video played'); 

					this.player.setCurrentTime(this.timeElapsed);
				}).catch(function(error) {
					alert('Something went wrong. Reload the page.');
				});
			}
		},
		mounted: function() {
			this.loadVideo();
		}
	}
</script>