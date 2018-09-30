<template>
	<div>
		<div v-for="day in schedule">
			<h1 class="font-weight-bold">{{ day.header }}</h1>
			<div v-for="broadcasts in day.broadcasts">
				<span>{{ broadcasts.name }}</span>
			</div>

		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				schedule: []
			}
		},
		mounted: function() {
			axios
				.get(process.env.MIX_APP_URL + '/w/api/schedule/')
				.then(response => {
					const data = response.data;

					this.schedule = data.reduce((accumulator, broadcast) => {
						const time = Moment.utc(broadcast.starts_at).local();
						const date = time.format('MM_DD_Y');
						const header = time.calendar(); 

						if (! accumulator.hasOwnProperty(date)) {
							accumulator[date] = {
								header: header, 
								broadcasts: []
							};
						}

						accumulator[date].broadcasts.push(broadcast);

						return accumulator;

					}, {});

					console.log(this.schedule);
					// for (let i = 0; i < data.length; i++) {
					// 	const broadcast = data[i];
					// 	const time = Moment.utc(broadcast.starts_at);

					// 	//console.log('UTC: ' + time.format('MM-DD-Y h:mm') + ', Local: ' + time.local().format('MM-DD-Y h:mm'));
					// 	console.log(time.local().calendar());
					// }
				});
		}
	}
</script>