<template>
	<component
		:is="layout"
		:class="pageName"
	>
		<router-view ref="router"></router-view>
		<broadcast-popup
			v-if="showBroadcastPopup"
			:broadcast="broadcast"
		/>
	</component>
</template>

<script>
	import DefaultLayout from './layouts/DefaultLayout'
	import NoHeaderLayout from './layouts/NoheaderLayout'
	import HostLayout from './layouts/HostLayout'
	import BroadcastPopup from './components/BroadcastPopup'
	import { mapState } from 'vuex'

	export default {
		components: {
			DefaultLayout,
			NoHeaderLayout,
			HostLayout,
			BroadcastPopup
		},
		data: function() {
			return {
				showBroadcastPopup: false,
				broadcast: null
			}
		},
		computed: {
			...mapState([
				'layout',
			]),
			pageName: function() {
				return this.$route.name;
			}
		},
		methods: {
		    broadcastStatusChanged: function(broadcast) {
		    	if ((this.$router.currentRoute.name == 'broadcast' 
		    		&& this.$refs.router.broadcast.id == broadcast.id)
		    		|| this.$router.currentRoute.name == 'host') {

		    		this.$refs.router.$_broadcastMixin_broadcastStatusChanged(broadcast);
		    	}
		    }
		},
	    created: function() {
			Echo.channel('main')
				.listen('BroadcastOpen', data => {
					this.broadcastStatusChanged(data);
				})
				.listen('BroadcastStarting', data => {
					this.broadcastStatusChanged(data)
				})
				.listen('BroadcastClosed', data => {
					this.broadcastStatusChanged(data);
				});
	    }
	}
</script>
