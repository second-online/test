<template>
	<div class="container-fluid p-0 bg-light-grey">
<!-- 		Homepage
		<router-link v-bind:to="{ name: 'broadcast', params: { broadcast_id: 3} }">Broadcast</router-link>
		<router-link v-bind:to="{ name: 'sermons' }">Sermons</router-link>
		<router-link v-bind:to="{ name: 'host' }">Host</router-link> -->
		<div class="latest-sermon d-flex flex-grow-1">
			<div class="row no-gutters">
				<div class="col-6 d-flex mb-60 flex-column move-up">
					<div class="d-flex ml-30 ml-md-60 flex-column justify-content-center flex-grow-1">
						<h1 class="huge text-white">{{ broadcast.sermon.title }}</h1>
						<span class="text-muted">Today at 3:00 pm<br>is our next broadcast</span>
					</div>
					<span class="ml-30 ml-md-60 text-white font-weight-bold text-uppercase line-height-1">View Schedule</span>
				</div>
				<div class="col-7 col-lg-5 offset-5 offset-lg-3 sermon-image-container mb-60">
					<img :src="broadcast.sermon.image">
				</div>
	 			<div class="col-3 offset-3 d-none d-lg-flex flex-column py-60 pr-30 pr-md-60 move-up">
	 				<div class="flex-grow-1">
	 					<span class="d-block text-white font-weight-bold">Speaker</span>
	 					<span class="d-block mb-60 text-muted">Pontius Pilate</span>
	 					<span class="d-block text-white font-weight-bold">Broadcasts</span>
	 					<span class="d-block text-muted">{{ broadcastDates }}</span>
	 				</div>
					<p class="mb-0 text-muted">{{ broadcast.sermon.description }}</p>
				</div>				
			</div>
		</div>
		<!-- <p class="py-60">something elsesomething elsesomething elsesomething elsesomething elsesomething elsesomething elsesomething elsesomething elsesomething else</p> -->
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
			},
			broadcastDates: function() {
				const moment = Moment.utc(this.broadcast.starts_at).local()
				const starts = moment.format('MMM D');
				const ends = moment.add(1, 'weeks').format('MMM D');

				return starts + ' - ' + ends;
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