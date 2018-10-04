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
				v-bind:time-elapsed="timeElapsed"
				ref="video"
				class="broadcast-video"
			/> 
		</div>
		<broadcast-chat
			v-bind:show-chat="showChat"
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
				showVideo: false,
				showChat: false,
			}
		},
		computed: {
			videoId: function() {
				if (this.broadcast.sermon === undefined) {
					return this.broadcast.trailer.link;
				}
				return this.broadcast.sermon.vimeo_id;
			},
			timeElapsed: function() {
				return (this.broadcast.time_elapsed !== undefined) ? this.broadcast.time_elapsed : 0;
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
				});
		},
		methods: {
			setData: function(data) {
				this.broadcast = data.broadcast;

				switch (data.status) {
					case 'broadcast_closed':
						this.broadcastClosed();
						break;
					case 'broadcast_open':
						this.broadcastOpen();
						break;
					case 'broadcast_in_progress':
						this.broadcastInProgress();
						break;
					case 'broadcast_ended':
						this.broadcastEnded();
						break;
				}
			},
	        broadcastClosed: function() {
	        	// Display some message
	        	this.showVideo = this.showVideo ? true : false;
	        	this.showChat = false;
	        }, 
			broadcastOpen: function() {
				this.showVideo = true;
				this.showChat = true;
			},
			broadcastInProgress: function() {
				this.showVideo = true;
				this.showChat = true;
			},
	        broadcastEnded: function() {
	            this.showVideo = false;
	            this.showChat = true;
	        },
		    goBack () {
				window.history.length > 1
					? this.$router.go(-1)
					: this.$router.push('/');
		    }
		}
	}
</script>
