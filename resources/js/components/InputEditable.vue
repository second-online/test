<template>
	<span
		class="d-block"
		contenteditable="contenteditable"
		v-on:input="updateInput"
		v-on:keydown.enter="enterPressed"
		placeholder="write a comment.."
		ref="input"
	></span>
</template>

<script>
  export default {
  		props: {
  			value: String
  		},
  		watch: {
  			value: function(newVal, oldVal) {
 				if (newVal.length == 0) {
 					this.$refs.input.innerText = '';
 				}
  			}
  		},
		methods: {
			updateInput: function(e) {
				this.$emit('input', e.target.innerText);
			},
			enterPressed: function(e) {
				this.$emit('key-down-enter');
				e.preventDefault();
			}
		}
	}
</script>