<template>
	<div
		v-bind:class="{ 'overflow-hidden' : isMenuActive }"
		class="d-flex flex-column h-100"
	>
		<the-header @menu-toggled="isMenuActive = $event"/>
		<main class="d-flex flex-grow-1 mh-0" role="main">
			<slot/>
		</main>
	</div>
</template>

<script>
	import TheHeader from '../components/TheHeader'

	export default {
		components: {
			TheHeader
		},
		data: function() {
			return {
				isMenuActive: false,
			}
		}
	}
</script>