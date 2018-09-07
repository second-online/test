<template>
	<div id="broadcast-page">
		<div class="broadcast-video">
			<span class="back" v-on:click="goBack">back</span>
			<youtube-player 
				v-if="showVideo"
				v-on:video-ended="videoEnded"
				v-bind:video-id="broadcast.sermon.youtube_id"
			/>
		</div>
		<broadcast-chat
			v-if="showChat"
			v-bind:broadcastId="broadcast.id"
		/>
	</div>
</template>

<script>
	import YoutubePlayer from '../components/YoutubePlayer'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			YoutubePlayer,
			BroadcastChat
		},
		data: function() {
			return {
				broadcast: {},
				showVideo: false,
				showChat: false
			}
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get('http://second.test/w/api/broadcasts/' + to.params.broadcast_id)
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
				if (data.user != null) {
					this.$store.state.user = data.user;
				}

				this.broadcast = data.broadcast;
				this.showVideo = true;
				this.showChat = true;
			},
	        videoEnded: function() {
	            //this.showVideo = false;
	        }, 
		    goBack () {
				window.history.length > 1
					? this.$router.go(-1)
					: this.$router.push('/');
		    }
		}
	}
</script>