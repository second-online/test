<template>
	<div class="d-flex flex-column flex-grow-1 mh-0 video-sidebar">
		<div class="d-flex flex-column flex-grow-1 overflow-y">
			<slot/>
			<slot name="notes"/>
		</div>
	</div>
</template>