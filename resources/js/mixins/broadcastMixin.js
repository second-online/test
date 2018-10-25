export default {
	computed: {
		$_broadcastMixin_isBroadcastLoaded: function() {
			return this.broadcast !== null;
		},
		$_broadcastMixin_isBroadcastClosed: function() {
			return this.broadcast.status == 'broadcast_closed';
		},
		$_broadcastMixin_isBroadcastOpen: function() {
			return this.broadcast.status != 'broadcast_closed';
		},
		$_broadcastMixin_isBroadcastInProgress: function() {
			return this.broadcast.status == 'broadcast_in_progress';
		},
		$_broadcastMixin_isBroadcastLive: function() {
			return this.broadcast.live;
		},
		$_broadcastMixin_hasNotes: function() {
			return this.broadcast.sermon.notes !== null;
		},
		$_broadcastMixin_videoId: function() {
			return this.$_broadcastMixin_isBroadcastInProgress
				? this.broadcast.sermon.vimeo_id
				: this.broadcast.trailer.link;
		},
		$_broadcastMixin_timeElapsed: function() {
			return this.broadcast.time_elapsed !== undefined ? this.broadcast.time_elapsed : 0;
		}
	},
	methods: {
		$_broadcastMixin_broadcastStatusChanged: function(broadcast) {
			this.broadcast = broadcast;
		}
	}
}