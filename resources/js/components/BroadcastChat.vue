<template>
	<div class="d-flex flex-column flex-grow-1 mh-0 bg-light-grey ">
		<template v-if="showChat">
			<div id="broadcast-comments" class="d-flex flex-column flex-grow-1 overflow-y">
				<div
					v-for="(comment, key) in comments"
					class="d-flex px-40 py-16 flex-shrink-0"
					v-bind:class="{ 'pt-40': key == 0 }"
				>	
					<img src="https://cdn.dribbble.com/users/1166392/avatars/normal/7765da9b241339c9885a24bb0c48a363.jpg?1499245430" class="mr-20 flex-shrink-0 image-faker"></span>
					<div class="flex-grow-1">
						<div>
							<span class="large font-weight-bold">{{ comment.user.name }}</span>
							<span v-if="comment.user.is_host" class="d-none pl-8 text-muted">Host</span>
						</div>
						<div class="mt-4">
							<span class="large">{{ comment.text }}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex chat-comment-box px-40 bg-white overflow-y">
				<form
					v-if="isUserAuthenticated"
					class="w-100 m-auto"
				>	
					<comments-textarea
						v-bind:value="newComment"
						v-on:input="newComment = $event"
						v-on:submit="submitComment"
						ref="commentsTextarea"
					/>
				</form>
				<span
					v-else
					v-on:click="login"
					class="d-block p-30 font-weight-bold text-center"
				>Sign in to chat</span>
			</div>
		</template>
		<template v-else>	
			<div class="d-flex flex-grow-1 align-items-center justify-content-center">
				<span class="text-muted">The chat is closed.</span>
			</div>
		</template>
	</div>
</template>

<script>
	import CommentsTextarea from '../components/CommentsTextarea'

	export default {
		props: {
			showChat: Boolean,
			broadcastId: Number
		},
		components: {
			CommentsTextarea,
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
			user: function() {
				return this.$store.state.user;
			},
			isUserAuthenticated: function() {
				return this.$store.getters.isUserAuthenticated;
			},
			isHost: function() {
				return this.$store.getters.isUserHost;
			}
		},
		watch: {
			showChat: function(value) {
				if (value) {
					this.enableChat();
				} else {
					this.disableChat();
				}
			}
		},
		methods: {
			submitComment: function() {
				if (this.isLoading) { return; }

				if (! this.isUserAuthenticated) { return; }

				axios
					.post(process.env.MIX_APP_URL + '/w/api/broadcasts/' + this.broadcastId + '/comments', {
						commentId: this.newCommentId,
						text: this.newComment,
						isHost: this.isHost
					})
					.then(response => {
						// Flip the array to start at the end would be better?
						const index = this.comments.findIndex(comment => {
							return comment.localCommentId == response.data.local_id;
						});

						this.comments[index] = response.data;
					})
					.catch(error => {
						// Do something if comment fails
						// this.newComment = this.cachedComment;
					})
					.then(() => {
						this.isLoading = false;
					});

				const comment = {
					localCommentId: this.newCommentId,
					text: this.newComment,
					user: this.user
				};

				this.publishComment(comment);

				this.cachedComment = this.newComment;
				this.newComment = '';
				this.newCommentId++;
				this.isLoading = true;
			},
			publishComment: function(comment) {
				const el = document.getElementById('broadcast-comments');
				const distanceFromBottom = el.scrollHeight - el.clientHeight - el.scrollTop;
				 				 
				this.comments.push(comment);
				
				setTimeout(() => {
					if (distanceFromBottom < 150) {
						el.scrollTop = el.scrollHeight - el.clientHeight;
					}
				}, 50);
			},
			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			},
			enableChat: function() {
				Echo.channel('broadcast.chat.' + this.broadcastId)
					.listen('BroadcastCommentCreated', comment => {
						this.publishComment(comment);
				});			
			},
			disableChat: function() {
				Echo.leave('broadcast.chat.' + this.broadcastId);
				console.log('chat disabled');
			}
		},
		mounted: function() {
			if (this.showChat) {
				this.enableChat();
			}
		},
		beforeDestroy: function() {
			this.disableChat();
			// this.comments = null;
		}
	}
</script>

