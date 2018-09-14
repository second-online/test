<template>
	<div id="vimeo-player" class="embed-responsive embed-responsive-16by9"></div>
</template>

<script>
	export default {
		mounted: function() {
		    var options = {
		        id: 59777392,
		        loop: true,
		        //playinline
		    };

		    var player = new Vimeo('vimeo-player', options);
		}
	}
</script>