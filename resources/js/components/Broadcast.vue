<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1 bg-light-grey">
		<template v-if="showBroadcastPage">
			<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black justify-content-center video-content">
				<div class="d-flex px-30 px-md-60 align-items-center header justify-content-between video-header">
					<span
						v-on:click="goBack"
						class="close"
					></span>
					<span class="text-white clickable">Show notes</span>
				</div>
				<vimeo-player 
					v-if="showVideo"
					v-on:broadcast-ended="broadcastEnded"
					v-bind:video-id="videoId"
					v-bind:time-elapsed="timeElapsed"
					class="px-0 px-lg-60"
					ref="video"
				/> 
			</div>
			<broadcast-chat
				v-bind:show-chat="showChat"
				v-bind:broadcast-id="broadcast.id"
				ref="broadcastChat"
				class="video-sidebar"
			/>
		</template>
		<template v-else>
			<div
				v-if="hasBroadcastLoaded"
				class="d-flex px-40 flex-column flex-grow-1"
			>
				<div class="position-absolute py-30">
					<span
						v-on:click="goBack"
						class="close"
					></span>
				</div>
				<div class="d-flex flex-grow-1 align-items-center justify-content-center">
					<div class="text-center">				
						<span class="d-block">The next broadcast is<br> {{ nextBroadcastTime }}</span>
						<span class="pt-20 d-block font-weight-bold text-underline">View schedule</span>
					</div>
				</div>
			</div>
		</template>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import VideoSidebar from '../components/VideoSidebar'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			VimeoPlayer,
			VideoSidebar,
			BroadcastChat
		},
		data: function() {
			return {
				broadcast: {},
				showVideo: false,
				showChat: false,
				previousPage: {}
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
			},
			showBroadcastPage: function() {
				return this.showVideo || this.showChat;
			},
			hasBroadcastLoaded: function() {
				return this.broadcast.id === undefined ? false : true;
			},
			nextBroadcastTime: function() {
				return Moment.utc(this.broadcast.starts_at)
					.local()
					.format('dddd [at] h:mm a');
			}
		},
		beforeRouteEnter (to, from, next) {
			next(vm => vm.setData(from));
		},
		methods: {
			setData: function(from) {
				this.previousPage = from;
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
		    goBack () {
		    	if (this.previousPage.name === null) {
		    		this.$router.push({name: 'home'});
		    	} else {
		    		this.$router.go(-1);
		    	}
		    }
		},
		created: function() {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/broadcasts/' + this.$route.params.broadcast_id)
				.then(response => {
					this.broadcast = response.data;

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
				})
				.catch(error => {
					error.response.status === 404 ? next('404') : next('/');
				});
		}
	}
</script>
