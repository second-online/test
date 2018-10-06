<template>
	<div class="container-fluid">
		<template v-for="(sermon, index) in sermons">
			<div
				v-if="index % 2 == 1"
				class="row"
			>
				<div class="col">
					<img class="w-100" v-bind:src="sermons[index-1].image">
				</div>
				<div class="col">
					<img class="w-100" v-bind:src="sermon.image">
				</div>
			</div>
		</template>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				sermons: []
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