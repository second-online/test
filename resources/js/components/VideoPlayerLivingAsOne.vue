<template>
	<div>
		<slot/>
	</div>
</template>

<script>
	export default {
		mounted: function() {
			const script = document.createElement('script');

			script.src = '//control.livingasone.com/webplayer/require.js'
			script.setAttribute('data-main', '//control.livingasone.com/webplayer/loader.js');

			document.body.appendChild(script);
		}
	}
</script>