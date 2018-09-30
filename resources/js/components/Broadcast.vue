<template>
	<div class="d-flex flex-column flex-md-row h-100">
		<div class="broadcast-video-wrapper flex-shrink-0 flex-md-shrink-1 flex-md-grow-1 bg-black">
			<div class="px-40 py-20">
				<span class="back text-white" v-on:click="goBack">back</span>
			</div>
			<vimeo-player 
				v-if="showVideo"
				v-on:video-ended="videoEnded"
				v-bind:video-id="broadcast.sermon.youtube_id"
				class="broadcast-video"
			/> 
		</div>
		<broadcast-chat
			v-if="showChat"
			v-bind:broadcastId="broadcast.id"
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
				showChat: false
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
				this.broadcast = data;
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
