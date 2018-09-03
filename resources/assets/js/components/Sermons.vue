<template>
	<div>
		<div
			v-for="sermon in this.$store.state.sermons"
			v-bind:key="sermon.id"
			v-on:click="showSermon(sermon)"
		>
			<!-- <router-link v-bind:to="{ name: 'sermon', params: {sermonId: sermon.id} }">Sermons</router-link> -->
			{{ sermon.title }}
		</div>
	</div>
</template>

<script>
	export default {

		methods: {
			showSermon(sermon) {
				this.$router.push({ name: 'sermon', params: { sermon_id: sermon.id }, props: { sermon: sermon} });
			}
		},
		mounted: function() {
			axios
				.get('http://second.test/w/api/sermons/')
				.then(response => {
					this.$store.state.sermons = response.data.sermons;
				});
		}
	}
</script>