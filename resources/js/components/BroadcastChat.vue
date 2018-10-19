<template>
	<div class="d-flex flex-column flex-grow-1">
		<div
			:id="scrollContainerId"
			class="d-flex flex-column flex-grow-1 border-bottom overflow-y"
		>
			<slot/>

			<div
				v-if="showChat"
				class="px-30 px-md-40 pt-36"
			>
				<div
					v-for="comment in comments"
					:key="comment.id"
					:id="'comment-' + comment.id"
					class="d-flex mb-36 flex-shrink-0"
				>	
					<img
						:src="comment.user.profile_picture"
						class="profile-picture mt-2 mr-24 flex-shrink-0"
					>
					<div class="flex-grow-1">
						<div class="mb-4">
							<span class="font-weight-bold">{{ comment.user.name }}</span>
							<span
								v-if="comment.user.is_host"
								class="ml-4 text-muted"
							>Host</span>
						</div>
						<div>
							<span>{{ comment.text }}</span>
						</div>
					</div>
				</div>
			</div>
			<div
				v-else
				class="d-flex flex-grow-1 align-items-center justify-content-center"
			>
				<span class="text-muted">The chat is closed.</span>
			</div>
		</div>
		<div 
			v-if="showChat"
		>
			<comment-form
				v-if="isUserAuthenticated"
				:value="newComment"
				@input="newComment = $event"
				@submit="submitComment"
				class="bg-white"
			/>
			<span
				v-else
				@click="login"
				class="d-block p-30 font-weight-bold text-center bg-white"
			>Sign in to chat</span>
		</div>
	</div>
</template>

<script>
	import CommentForm from '../components/CommentForm'
	import chatMixin from '../mixins/chatMixin'
	import { mapState } from 'vuex'
	import { mapGetters } from 'vuex'

	export default {
		props: {
			scrollContainerId: String,
			showChat: Boolean,
			broadcastId: Number
		},
		components: {
			CommentForm,
		},
		mixins: [chatMixin],
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
			...mapState([
				'user',
			]),
			...mapGetters([
				'isUserAuthenticated',
				'isUserHost'
			])
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
					.post('/w/api/broadcasts/' + this.broadcastId + '/comments', {
						commentId: this.newCommentId,
						text: this.newComment,
						isHost: this.isUserHost
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

				this.$_chatMixin_publishComment(comment);

				this.cachedComment = this.newComment;
				this.newComment = '';
				this.newCommentId++;
				this.isLoading = true;
			},

			login: function() {
				this.$router.push({ name: 'login', query: { redirect: this.$route.path } });
			},
			enableChat: function() {
				Echo.channel('broadcast.chat.' + this.broadcastId)
					.listen('BroadcastCommentCreated', comment => {
						this.$_chatMixin_publishComment(comment);
				});			
			},
			disableChat: function() {
				Echo.leave('broadcast.chat.' + this.broadcastId);
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
