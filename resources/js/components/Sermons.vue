<template>
	<div class="container-fluid">
<!-- 		<div
			v-for="group in groupedSermons"
			class="row"
		>
			<div
				v-for="sermon in group"
				class="col-6"
			>
				<img v-bind:src="sermon.image" class="w-100">
			</div>
		</div> -->
		<div class="row">
			<div v-for="sermon in sermons" class="col-12 col-md-6 col-xl-4">
				<img v-bind:src="sermon.image" class="w-100">
				<span class="d-block">{{ sermon.title }}</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				sermons: [],
				itemsPerRow: 2
			}
		},
		computed: {
			groupedSermons: function() {
				return this.sermons.reduce((accumulator, sermon, index) => {
					if (index % this.itemsPerRow == 0) {
						accumulator.push([sermon]);
					} else {
						accumulator[accumulator.length - 1].push(sermon);
					} 

					return accumulator;
				}, []);
			}
		},
		methods: {
			fetchSermons() {
				axios
					.get(process.env.MIX_APP_URL + '/w/api/sermons/')
					.then(response => {
						this.sermons = response.data.sermons;
					});
			},
			openSermon(sermon) {
				this.$router.push({
					name: 'sermon',
					params: { sermon_id: sermon.id }
				});
			}
		},
		created: function() {
			this.fetchSermons();
		}
	}
</script>