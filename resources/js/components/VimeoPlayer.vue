<template>
	<div id="vimeo-player" class="embed-responsive embed-responsive-16by9"></div>
</template>

<script>
	export default {
		data: function() {
			return {
				player: {}
			}
		},
		methods: {
			play: function() {
				this.player.play();
				// this.player.play().then(function() {
				// 	console.log('video played');
				//     // the video was played
				// }).catch(function(error) {
				//     switch (error.name) {
				//         case 'PasswordError':
				//             // the video is password-protected and the viewer needs to enter the
				//             // password first
				//             break;

				//         case 'PrivacyError':
				//             // the video is private
				//             break;

				//         default:
				//             // some other error occurred
				//             break;
				//     }
				// });
			}
		},
		mounted: function() {
		    var options = {
		        id: 59777392,
		        // autoplay: true
		        
		        //playinline
		    };

		    this.player = new Vimeo('vimeo-player', options);
			this.player.setVolume(0);	

			this.player.ready().then(() => {
				console.log('ready');
				this.play();
			});
		}
	}
</script>