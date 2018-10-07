<template>
	<div class="container-fluid px-30 px-xl-60">
		<div class="row justify-content-center">
			<div class="col">
				<h1 class="text-center">Each Tuesday we publish a new sermon.</h1>
			</div>
		</div>
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
<!-- 		<div class="row">
			<div class="col-1">
				<div class="row bg-warning">sidebar</div>
			</div>
			<div class="col-10">
				<div class="row">
					<div v-for="sermon in sermons" class="col-6">
						<img v-bind:src="sermon.image" class="w-100">
						<span class="d-block">{{ sermon.title }}</span>
					</div>
				</div>
			</div>
			<div class="col-1">
				<div class="row bg-warning">sidebar</div>
			</div>
		</div> -->

		<div class="row">
			<div class="col-1 d-none d-xl-block">
				<span class="small text-vertical text-uppercase">
					Today at 3:00 pm<br>is the next broadcast
				</span>
			</div>
			<div class="col-12 col-xl-10">
				<div class="row">
					<div
						v-for="sermon in sermons"
						v-on:click="openSermon(sermon)"
						class="col-12 col-md-6"
					>
						<img v-bind:src="sermon.image" class="w-100">
						<span class="d-block">{{ sermon.title }}</span>
					</div>
				</div>
			</div>
<!-- 			<div class="col-1 d-none d-xl-block">
				<div class="w-100 h-100 bg-warning">4</div>
			</div> -->
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