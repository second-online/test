<template>
	<div class="d-flex flex-column flex-md-row h-100">
		<div class="broadcast-video-wrapper flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black">
			<div class="px-40 py-20">
				<span class="back text-white" v-on:click="goBack">back</span>
			</div>
			<vimeo-player 
				v-if="showVideo"
				v-on:broadcast-ended="broadcastEnded"
				v-bind:video-id="videoId"
				v-bind:seconds-elapsed="videoElapsedTime"
				ref="video"
				class="broadcast-video"
			/> 
		</div>
		<broadcast-chat
			v-if="showChat"
			v-bind:broadcast-id="broadcast.id"
			ref="broadcastChat"
			class="broadcast-chat-wrapper"
		/>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			VimeoPlayer,
			BroadcastChat
		},
		data: function() {
			return {
				broadcast: {},
				videoId: '',
				videoElapsedTime: 0,
				showVideo: false,
				showChat: false,
			}
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/broadcasts/' + to.params.broadcast_id)
				.then(response => {
					next(vm => vm.setData(response.data));
				})
				.catch(error => {
					(error.response.status === 404) ? next('404') : next('/');
				})
				.then(() => {
					// delete this?
				});
		},
		methods: {
			setData: function(data) {
				
				// const currentTime = Moment.utc(data.current_time);
				// const opensAt = Moment.utc(data.broadcast.opens_at);
				// const startsAt = Moment.utc(data.broadcast.starts_at);
				// const endsAt = Moment.utc(data.broadcast.ends_at);

				// this.broadcast = data.broadcast;

				// console.log(data.current_time);
				// console.log(data.broadcast.opens_at);
				// console.log(data.broadcast.starts_at);
				// console.log(data.broadcast.ends_at);

				// //console.log(startsAt.subtract(10, 'minutes').format('Y-m-d H:mm'));

				// console.log(currentTime.isAfter(endsAt));

				// // The broadcast has ended but the chat is still open.
				// if (currentTime.isAfter(endsAt)) {
				// 	this.broadcastEnded();
				// }
				// // The broadcast is in progress.
				// else if (currentTime.isAfter(startsAt)) {
				// 	const elapsedSeconds = currentTime.diff(startsAt, 'seconds');
				// 	this.broadcastInProgress(elapsedSeconds);
				// }
				// // The broadcast chat is open.
				// else if (currentTime.isAfter(opensAt)) {
				// 	this.broadcastOpen();
				// }
				// // The broadcast isn't open.
				// else {

				// }
			},
			broadcastOpen: function() {
				this.videoId = this.broadcast.trailer.link;
				this.showVideo = true;
				this.showChat = true;
			},
			broadcastInProgress: function(secondsElapsed = 0) {
				this.videoId = this.broadcast.sermon.vimeo_id;
				this.videoElapsedTime = secondsElapsed;
				this.showVideo = true;
				this.showChat = true;
			},
	        broadcastEnded: function() {
	            this.showVideo = false;
	            this.showChat = true;
	        },
	        broadcastClosed: function() {
	        	// Display some message
	        	this.showVideo = false;
	        	this.showChat = false;
	        }, 
		    goBack () {
				window.history.length > 1
					? this.$router.go(-1)
					: this.$router.push('/');
		    }
		}
	}
</script>
