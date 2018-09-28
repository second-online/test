<template>
	<textarea
		v-bind:value="value"
		v-on:input="input"
		v-on:keydown.enter="submit"
		class="d-block py-20 px-0 w-100 border-0"
		placeholder="Write a comment.."
		rows="1"
	></textarea>
</template>

<script>
	export default {
		props: {
			value: String
		},
		watch: {
			value: function(newValue) {				
				if (newValue.length == 0) {
					this.clearTextarea();
				}
			}
		},
		methods: {
			input: function(e) {
				this.$emit('input', e.target.value);
				this.resizeTextarea();
			},
			submit: function(e) {
				this.$emit('submit');
				e.preventDefault();
			},
			resizeTextarea: function() {
				this.$el.style.height = 'auto';
				this.$el.style.height = this.$el.scrollHeight + 'px';
			},
			clearTextarea: function() {
				this.$el.value = '';
				this.resizeTextarea();
			}
		}
	}
</script>
