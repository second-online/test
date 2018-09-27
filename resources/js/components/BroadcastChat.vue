<template>
	<div class="d-flex flex-column h-100">
		<div id="broadcast-comments" class="d-flex flex-column flex-grow-1 overflow-y bg-light-grey">
			<div
				v-for="comment in comments"
				class="d-flex px-40 flex-shrink-0"
			>	
				<img src="https://cdn.dribbble.com/users/345970/avatars/normal/0092209c0eddd9d7a0cfaa54a92fd39d.png?1530163405" class="mt-24 mr-20 image-faker"></span>
				<div class="py-24 flex-grow-1 large">
					<div class="mb-4">
						<span class="d-inline-block large font-weight-bold">{{ comment.user.name }}</span>
					</div>
					<div>
						<span class="d-block large">{{ comment.text }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex chat-comment-box px-40 bg-white overflow-y">
			<form
				v-if="isUserAuthenticated"
				class="py-20 w-100 m-auto"
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
	</div>
</template>

<script>
	import CommentsTextarea from '../components/CommentsTextarea'
	import Editable from '../components/Editable'

	export default {
		props: {
			broadcastId: Number
		},
		components: {
			CommentsTextarea,
			Editable
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

				this.publishNewComment(comment);

				this.cachedComment = this.newComment;
				this.newComment = '';
				this.isLoading = true;
			},
			publishNewComment(comment) {
				const el = document.getElementById('broadcast-comments');
				let amountScrollable = el.scrollHeight - el.clientHeight;
				let distanceFromBottom = amountScrollable - el.scrollTop;
				 
				console.log('distanceFromBottom: ' + distanceFromBottom);
				 
				this.comments.push(comment);

				console.log('scrollable: ' + amountScrollable);

				setTimeout(function() {
					
					if (distanceFromBottom < 80) {
						amountScrollable = el.scrollHeight - el.clientHeight;
						el.scrollTop = amountScrollable;
						console.log('scrolled bottom');
					} else {
						console.log('dont scroll');
					}
					
					//console.log('client height: ' + el.clientHeight + ', scroll height: ' + el.scrollHeight + ', scroll top: ' + el.scrollTop);
				},0);
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
		},
		updated: function() {


		},
		beforeDestroy: function() {
			Echo.leave('broadcast.chat.' + this.broadcastId);

			// this.comments = null;
		}
	}
</script>