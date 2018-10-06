<template>
	<div class="d-flex no-gutters h-100 bg-warning">
		<div class="col">
			<router-link v-bind:to="{ name: 'home' }">Home</router-link>
			<vimeo-player 
				v-if="showVideo"
				v-on:broadcast-ended="broadcastEnded"
				v-bind:video-id="videoId"
				v-bind:time-elapsed="timeElapsed"
				ref="video"
			/> 
		</div>
		<div class="col bg-white">
			
<!-- 				<host-chat
				v-bind:previousComments="hostComments"
			/> -->
		</div>
		<div class="d-flex col">
			<broadcast-chat
				v-bind:show-chat="showChat"
				v-bind:broadcast-id="broadcast.id"
				ref="broadcastChat"
			/>
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
					(error.response.status === 401) ? next('login') : next('/');
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