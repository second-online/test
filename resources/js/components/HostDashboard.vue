<template>
	<div class="d-flex no-gutters flex-grow-1">
		<div class="d-flex col-4 flex-column flex-grow-1">
			<div class="bar px-30 px-md-40 d-flex align-items-center flex-shrink-0">
				<span class="menu-toggle"></span>
			</div>
			<vimeo-player 
				v-if="videoId !== null"
				:video-id="videoId"
				:time-elapsed="timeElapsed"
				:autoplay="autoplay"
				@broadcast-ended="broadcastEnded"
				class="video-container bg-black"
			/>
			<div
				v-if="showNotes"
				v-html="broadcast.sermon.notes"
				class="p-30 p-md-40 overflow-y"
			></div>
		</div>
		<div class="d-flex flex-column col-4 border-left border-right bg-light-grey">
			<div class="bar d-flex px-30 px-md-40 flex-shrink-0 align-items-center justify-content-between border-bottom">
				<span class="xlarge font-weight-bold">Host chat</span>
				<span class="small text-muted">
					<span
						v-if="hosts.length > 0"
						class="online-icon"
					></span>
					<span>{{ hosts.length }} {{ hosts.length == 1 ? 'host' : 'hosts' }} online</span>
				</span>
			</div>
			<host-chat
				:previousComments="hostComments"
				@hosts-update="hosts = $event"
				scroll-container-id="host-chat"
			/>
		</div>
		<div class="d-flex flex-column col-4">
			<div class="bar d-flex px-30 px-md-40 flex-shrink-0 align-items-center border-bottom">
				<span class="xlarge font-weight-bold">Broadcast chat</span>
			</div>
			<broadcast-chat
				:show-chat="showChat"
				:broadcast-id="broadcast.id"
				scroll-container-id="broadcast-comments"
				ref="broadcastChat"
			></broadcast-chat>
		</div>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import HostChat from '../components/HostChat'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			VimeoPlayer,
			HostChat,
			BroadcastChat
		},
		data: function() {
			return {
				broadcast: {},
				hosts: [],
				hostComments: [],
				autoplay: true
			}
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
				if (typeof this.broadcast.status !== 'undefined') {
					if (this.broadcast.status == 'broadcast_in_progress') {
						this.autoplay = true;

						return this.broadcast.sermon.vimeo_id;
					} else {
						this.autoplay = false;

						return this.broadcast.trailer.link;
					}
				}

				return null;				
			},
			timeElapsed: function() {
				return this.broadcast.time_elapsed !== undefined ? this.broadcast.time_elapsed : 0;
			},
			showNotes: function() {
				return this.broadcast.sermon
					? this.broadcast.sermon.notes ? true : false
					: false;
			},
			showChat: function() {
				if (typeof this.broadcast.status === 'undefined'
					|| this.broadcast.status == 'broadcast_closed') { 
					return false;
				}

				return true;
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
	        
	        }, 
			broadcastOpen: function() {
			
			},
			broadcastInProgress: function() {
			
			},
	        broadcastEnded: function() {
	        
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