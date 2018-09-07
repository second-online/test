<template>
	<div>
		<div class="comments-section">
			<div v-for="comment in comments">
				<span class="comments-username">{{ comment.user.name }}</span>
				<span>{{ comment.text }}</span>
			</div>
			<!-- avoid double enter with a sending flag -->
			<form v-if="isUserAuthenticated" id="broadcast-comment-form" v-on:submit.prevent="submitComment">
				<input type="text" placeholder="Write a comment.." v-model="newComment">
			</form>
			<div v-else>Login to chat.</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			broadcastId: Number
		},
		data: function() {
			return {
				comments: [],
				newComment: '',
				isLoading: false
			}
		},
		computed: {
			isUserAuthenticated: function() {
				return (typeof this.$store.state.user.id != 'undefined') ? true : false;
			}
		},
		methods: {
			submitComment: function() {
				if (this.isLoading) { return; }

				if (this.isUserAuthenticated) {
					const comment = {
						text: this.newComment,
						user: this.$store.state.user
					};
					this.comments.push(comment);
				}

				axios
					.post('http://second.test/w/api/broadcasts/' + this.broadcastId + '/comments', {
						text: this.newComment,
					})
					.then(response => {
						console.log('then');

						if (! this.isUserAuthenticated) {
							this.$store.state.user = response.data.user;
							this.comments.push(response.data);
						} 
					})
					.catch(error => {
						console.log('catch');
					})
					.then(() => {
						this.isLoading = false;
					});

				this.newComment = '';
				this.isLoading = true;
			}
		},
		mounted: function() {
			Echo.channel('broadcast.chat.' + this.broadcastId)
				.listen('BroadcastCommentCreated', comment => {
					this.comments.push(comment)
			});
		}
	}
</script>