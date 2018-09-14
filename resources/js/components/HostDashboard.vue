<template>
	<div class="container-fluid p-0">
		<div class="row no-gutters">
			<div class="col">
				<vimeo-player

				/>
			</div>
			<div class="col">
				<host-chat
					v-if="showHostChat"
					v-bind:previousComments="hostComments"
				/>
			</div>
			<div class="col">
				<broadcast-chat
					v-if="showBroadcastChat"
					v-bind:broadcastId="broadcastInProgress.id"
				/>
			</div>
		</div>
	</div>
</template>

<script>
	import VimeoPlayer from '../components/VimeoPlayer'
	import HostChat from '../components/HostChat'
	import BroadcastChat from '../components/BroadcastChat'

	export default {
		name: 'host-dashboard',
		data: function() {
			return {
				now: '',
				broadcasts: [],
				broadcastInProgress: {},
				hostComments: [],
				showHostChat: false,
				showBroadcastChat: false
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
				})
				.then(() => {
					// delete this?
				});
		},
		methods: {
			setData: function(data) {
				this.now = data.now;
				this.broadcasts = data.broadcasts;
				this.hostComments = data.host_comments;
				this.showHostChat = true;

				// const nows = Moment.utc('2018-09-13 09:51:00');
				// const broadcast = Moment.utc('2018-09-13 10:00:00').subtract(10, 'minutes');
				// console.log(broadcast.diff(nows));
				// console.log(broadcast - nows);
				// return;

				const now = Moment.utc(this.now);

				const inProgressBroadcast = this.broadcasts.find(function(broadcast) {
					const startsAt = Moment.utc(broadcast.starts_at).subtract(10, 'minutes');

					return startsAt.diff(now) <= 0;
				});

				if (typeof inProgressBroadcast !== 'undefined') {
					this.loadBroadcastChat(inProgressBroadcast);
				}
			},
			loadBroadcastChat: function(broadcast) {
				this.broadcastInProgress = broadcast;
				this.showBroadcastChat = true;
				console.log('loadBroadcastChat');
				console.log(broadcast);
			},
			hideBroadcastChat: function(broadcast) {
				this.showBroadcastChat = false;
				console.log('hideBroadcastChat');
			}
		},
		mounted: function() {

		}
	}
</script>