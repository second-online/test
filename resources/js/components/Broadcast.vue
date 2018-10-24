<template>
	<div class="d-flex flex-column flex-md-row flex-grow-1">
		<template v-if="isBroadcastOpen">
			<div class="position-relative d-flex flex-column flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 justify-content-center bg-black">
				<div class="d-flex mx-30 mx-md-60 align-items-center flex-shrink-0 justify-content-between bar video-header">
					<span
						@click="goBack"
						class="close"
					></span>
				</div>
				<video-player-vimeo
					:video-id="videoId"
					:time-elapsed="timeElapsed"
					class="px-0 px-lg-60"
				/> 
			</div>
			<broadcast-chat
				:broadcast-id="broadcast.id"
				scroll-container-id="broadcast-comments"
				ref="broadcastChat"
				class="video-sidebar bg-light-grey"
			>		 
				<div class="px-30 px-md-40 py-40 bg-white">
					<template v-if="broadcast.live">
						<h1>{{ broadcast.name }}</h1>
						<p>Join us this morning as we're live from Woodway campus</p>
					</template>
					<template v-else>
						<h1>{{ broadcast.sermon.title }}</h1>
						<p>{{ broadcast.sermon.description }}</p>
						<p
							v-if="broadcast.sermon.notes"
							@click="toggleNotes"
							class="font-weight-bold clickable"
						>
							{{ showNotes ? 'Hide notes' : 'See notes' }}
						</p>
						<div
							v-show="showNotes"
							v-html="broadcast.sermon.notes"
						></div>
					</template>
				</div>				 
			</broadcast-chat>
		</template>
		<template v-if="isBroadcastClosed">
			<span>{{ nextBroadcastTime }}</span>
		</template>
	</div>
</template>

<script>
	import VideoPlayerVimeo from '../components/VideoPlayerVimeo'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		components: {
			VideoPlayerVimeo,
			BroadcastChat
		},
		data: function() {
			return {
				notes: null,
				broadcast: {},
				showNotes: false,
				previousPage: null
			}
		},
		beforeRouteEnter (to, from, next) {
			next(vm => vm.setData(from));		
		},
		computed: {
			videoId: function() {
				if (this.broadcast.status == 'broadcast_in_progress'
					|| this.broadcast.status == 'broadcast_ended') {
					return this.broadcast.sermon.vimeo_id;
				} else {
					return this.broadcast.trailer.link;
				}
			},
			timeElapsed: function() {
				return this.broadcast.time_elapsed !== undefined ? this.broadcast.time_elapsed : 0;
			},
			nextBroadcastTime: function() {
				return Moment.utc(this.broadcast.starts_at)
					.local()
					.format('dddd [at] h:mm a');
			},
			isBroadcastOpen: function() {
				if (this.broadcast.status !== undefined &&
					this.broadcast.status !== 'broadcast_closed') {
					return true;
				}
				return false;
			},
			isBroadcastClosed: function() {
				if (this.broadcast.status !== undefined &&
					this.broadcast.status === 'broadcast_closed') {
					return true;
				}
				return false;
			}
		},
		methods: {
			setData: function(from) {
				this.previousPage = from;
			},
	        broadcastClosed: function(broadcast) {
	        	this.broadcast = broadcast;
	        }, 
			broadcastOpen: function(broadcast) {
				this.broadcast = broadcast;
			},
			broadcastInProgress: function(broadcast) {
				this.broadcast = broadcast;
			},
	        toggleNotes: function() {
	        	this.showNotes = !this.showNotes;
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
				.get('/w/api/broadcasts/' + this.$route.params.broadcast_id)
				.then(response => {
					const broadcast = response.data;

					switch (broadcast.status) {
						case 'broadcast_closed':
							this.broadcastClosed(broadcast);
							break;
						case 'broadcast_open':
							this.broadcastOpen(broadcast);
							break;
						case 'broadcast_in_progress':
							this.broadcastInProgress(broadcast);
							break;
						case 'broadcast_ended':
							this.broadcastOpen(broadcast);
							break;
					}					
				})
				.catch(error => {
					console.log(error);
				});	
		}
	}
</script>
