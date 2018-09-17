<template>
	<div class="comments-section">
		<div v-for="comment in comments">
			<span class="comments-username">{{ comment.user.name }}</span>
			<span>{{ comment.text }}</span>
		</div>
		<!-- avoid double enter with a sending flag -->
		<form v-if="isUserAuthenticated" id="broadcast-comment-form" v-on:submit.prevent="submitComment">
			<input type="text" placeholder="Write a comment.." v-model="newComment">
		</form>
		<div v-else v-on:click="login">Login to chat.</div>
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
				newCommentId: 1,
				cachedComment: '',
				isLoading: false,
			}
		},
		computed: {
			isUserAuthenticated: function() {
				return this.$store.getters.isUserAuthenticated;
			}
		},
		methods: {
			submitComment: function() {
				if (this.isLoading) { return; }

				if (! this.isUserAuthenticated) { return; }

				const localCommentId = this.newCommentId++;

				axios
					.post(process.env.MIX_APP_URL + '/w/api/broadcasts/' + this.broadcastId + '/comments', {
						commentId: localCommentId,
						text: this.newComment,
					})
					.then(response => {
						const index = this.comments.findIndex(comment => {
							return comment.localCommentId == response.data.local_id;
						});
						// flip the array to start at the end would be better.
						this.comments[index] = response.data;
					})
					.catch(error => {
						//console.log('catch');
						this.newComment = this.cachedComment;
					})
					.then(() => {
						this.isLoading = false;
					});

				const comment = {
					localCommentId: localCommentId,
					text: this.newComment,
					user: this.$store.state.user
				};

				this.comments.push(comment);
				this.cachedComment = this.newComment;
				this.newComment = '';
				this.isLoading = true;
			},
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			},
		},
		mounted: function() {
			Echo.channel('broadcast.chat.' + this.broadcastId)
				.listen('BroadcastCommentCreated', comment => {
					this.comments.push(comment)
			});

			for (let i = 0; i < 10000; i++) {
				const comment = {
					text: 'this.newComment',
					user: this.$store.state.user
				};

				this.comments.push(comment)
			}
		},
		beforeDestroy: function() {
			Echo.leave('broadcast.chat.' + this.broadcastId);

			// this.comments = null;
		}
	}
</script>