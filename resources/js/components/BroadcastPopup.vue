<template>
	<div class="broadcast-popup m-30">
		<!-- <span class="m-12 close close-small close-dark align-self-end"></span> -->
		<div class="d-flex flex-column bg-white">
			<img :src="broadcast.sermon.image" class="w-100">
			<div class="p-30">
				<span class="d-block mb-4 large font-weight-bold">{{ broadcast.sermon.title }}</span>
				<span class="d-block small text-muted">Broadcast starts {{ startsIn }}</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			broadcast: Object
		},
		computed: {
			startsIn: function() {
				return Moment.utc(this.broadcast.starts_at).fromNow();
			}
		}
	}
</script>