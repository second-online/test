<template>
	<div
		contenteditable="true"
		v-on:input="input"
		v-on:keydown.enter="submit"
		data-placeholder="Write a comment.."
	>

	</div>
</template>

<script>
	export default {
		props: {
			value: String
		},
		// watch: {
		// 	value: function() {
		// 		this.$el.innerText = this.value;
		// 	}
		// },
		methods: {
			input: function() {
				console.log('input');
				this.$emit('input', this.$el.innerText);
			},
			submit: function(e) {
				this.$emit('submit');
				e.preventDefault();
			}
		}
	}
</script>