<template>
	<div class="home d-flex flex-grow-1 bg-warning">
<!-- 		Homepage
		<router-link v-bind:to="{ name: 'broadcast', params: { broadcast_id: 3} }">Broadcast</router-link>
		<router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link>
		<router-link v-bind:to="{ name: 'host' }">Host</router-link> -->
		<h1 class="huge">{{ broadcast.sermon.title }}</h1>
		<p>{{ startTime }}</p>
		<img :src="broadcast.sermon.image" class="w-50">
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				broadcast: {}
			}
		},
		computed: {
			route: function() {
				return this.$route.params.username
			},
			startTime: function() {
				return Moment.utc(this.broadcast.starts_at)
					.local()
					.calendar(null, {
    sameDay: '[Today at] H:mm a',
    nextDay: '[Tomorrow]',
    nextWeek: 'dddd',
    lastDay: '[Yesterday]',
    lastWeek: '[Last] dddd',
    sameElse: 'DD/MM/YYYY'
});
			}
		},
		created: function() {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/broadcasts/next')
				.then(response => {
					this.broadcast = response.data;
				})
				.catch(error => {

				});
		}
	}
</script>