<template>
	<div class="container-fluid px-30 px-lg-60">
		<div class="row justify-content-center">
			<div class="col">
				<h1 class="py-60 huge text-md-center">Each Tuesday we<br class="d-none d-md-block"><span class="d-md-none">&nbsp;</span>publish a new sermon.</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-1 d-none d-xl-block">
				<span class="small line-height-1 text-vertical text-uppercase">
					<span class="d-block">Today at 3:00 pm</span>
					<span class="d-block pr-4">is the next broadcast</span>
				</span>
			</div>
			<div class="col-12 col-xl-10">
				<div class="row">
					<div
						v-for="sermon in sermons"
						v-bind:key="sermon.id"
						v-on:click="openSermon(sermon.id)"
						class="col-12 col-md-6"
					>
						<div class="mb-40 clickable">
							<img v-bind:src="sermon.image" class="w-100">
							<span class="d-block pt-40 text-center">{{ sermon.title }}</span>
						</div>
					</div>
				</div>
			</div>
<!-- 			<div class="col-1 d-none d-xl-block">
				<div class="w-100 h-100 bg-warning">4</div>
			</div> -->
		</div>
		<div class="row">
			<div class="col-12 my-30 text-center">
				<span
					v-if="showLoadMoreButton"
					v-on:click="loadSermons"
					class="d-inline-block px-48 py-20 font-weight-bold text-white bg-black"
				>Load more</span>
				<span v-else>That's all the sermons.</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				sermons: [],
				firstPage: process.env.MIX_APP_URL + '/w/api/sermons/',
				nextPage: null,
				showLoadMoreButton: false
			}
		},
		computed: {
			endpoint: function() {
				return this.nextPage === null ? this.firstPage : this.nextPage;
			}
		},
		methods: {
			loadSermons() {
				axios
					.get(this.endpoint)
					.then(response => {
						this.sermons.push(...response.data.data);
						this.nextPage = response.data.next_page_url;
						this.showLoadMoreButton = this.nextPage === null ? false : true;
					});
			},
			openSermon(sermonId) {
				this.$router.push({
					name: 'sermon',
					params: { sermon_id: sermonId }
				});
			}
		},
		created: function() {
			this.loadSermons();
		}
	}
</script>