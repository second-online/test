<template>
	<section class="narrow-container m-auto">
		<section
			v-for="day in schedule"
			class="mb-40"
		>
			<h1 class="mt-80 mb-56 line-height-1">{{ day.title }}</h1>
			<div
				v-for="broadcast in day.broadcasts"
				class="row no-gutters py-24 border-bottom"
			>
				<span class="col-2 font-weight-bold">{{ broadcast.time }}</span>
				<div class="col-8 d-flex pl-40 pr-60 flex-grow-1">
					<span class="flex-grow-1">Entrance to the Kingdom</span>
					<span
						v-if="broadcast.live"
						class="text-danger"
					>Live broadcast</span>
				</div>
				<span class="col-2 font-weight-bold text-right text-muted">Get reminder</span>
			</div>
		</section>
	</section>
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
					this.schedule = response.data.reduce((accumulator, broadcast) => {
						const time = Moment.utc(broadcast.starts_at).local();
						const key = time.format('MM_DD_Y');
						const title = time.calendar();

						if (! accumulator.hasOwnProperty(key)) {
							accumulator[key] = {
								title: title, 
								broadcasts: []
							};
						}

						broadcast.time = time.format('h:mm a');

						accumulator[key].broadcasts.push(broadcast);

						return accumulator;

					}, {});

					console.log(this.schedule);
				});
		}
	}
</script>