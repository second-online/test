<template>
	<div class="container-fluid p-0 h-100">
		<div class="row no-gutters h-100">
			<div class="col-md-8 h-100 bg-black">
				<span class="back" v-on:click="goBack">back</span>
				<div class="broadcast-video px-40">
					<vimeo-player 
						v-if="showVideo"
						v-on:video-ended="videoEnded"
						v-bind:video-id="broadcast.sermon.youtube_id"
					/> 
				</div>
			</div>
			<div class="col-md-4 h-100 bg-light-grey">
				<broadcast-chat
					v-if="showChat"
					v-bind:broadcastId="broadcast.id"
				/>
			</div>
		</div>
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
