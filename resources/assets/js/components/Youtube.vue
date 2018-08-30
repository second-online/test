<template>
	<div class="youtube">
		<span style="color:white">Youtube video {{ id }}</span>
	</div>
</template>

<script>
	export default {
		props: [
			'id'
		],
		mounted() {
			console.log('youtube mounted');
		}
	}
</script>