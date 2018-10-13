<template>
	<div class="d-flex no-gutters flex-grow-1 bg-white">
		<div class="col-4">
			<vimeo-player 
				v-if="showVideo"
				@broadcast-ended="broadcastEnded"
				:video-id="videoId"
				:time-elapsed="timeElapsed"
				ref="video"
			/> 
		</div>
		<div class="d-flex col-4 bg-white border-right">
			<host-chat
				v-if="hostComments.length > 0"
				:previousComments="hostComments"
				scroll-container-id="host-chat"
			/>
		</div>
		<div class="d-flex col-4">
			<broadcast-chat
				:show-chat="showChat"
				:broadcast-id="broadcast.id"
				scroll-container-id="broadcast-comments"
				ref="broadcastChat"
			>
			</broadcast-chat>
		</div>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import HostChat from '../components/HostChat'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		data: function() {
			return {
				broadcast: {},
				hostComments: [],
				showVideo: false,
				showChat: false
			}
		},
		components: {
			VimeoPlayer,
			HostChat,
			BroadcastChat
		},
		beforeRouteEnter (to, from, next) {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/host/dashboard')
				.then(response => {
					next(vm => vm.setData(response.data));
				})
				.catch(error => {
					if (error.response.status === 401) { 
						next('login');
					} else {
						next('/');
					}
				});
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
		methods: {
			setData: function(data) {
				this.broadcast = data.broadcast;
				this.hostComments = data.host_comments;

				switch (this.broadcast.status) {
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
		},
		beforeDestroy: function() {
			// this.now= null,
			// this.broadcasts= null,
			// this.broadcastInProgress= null,
			// this.hostComments= null,
			// this.showHostChat= null,
			// this.showBroadcastChat= null
		}
	}
</script>