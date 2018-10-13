<template>
	<div class="d-flex flex-column flex-grow-1">
		<div
			:id="scrollContainerId"
			class="d-flex flex-column flex-grow-1 overflow-y"
		>
			<div class="mx-30 mx-md-40 mt-30">
				<div
					v-for="comment in comments"
					:key="comment.id"
					:id="'comment-' + comment.id"
					class="d-flex mb-40 flex-shrink-0"
				>	
					<img
						:src="comment.user.profile_picture"
						class="image-faker mt-3 mr-20 flex-shrink-0"
					>
					<div class="flex-grow-1">
						<div class="mb-4">
							<span class="font-weight-bold">{{ comment.user.name }}</span>
						</div>
						<div>
							<span style="color: #555">{{ comment.text }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<comment-form
			:value="newComment"
			@input="newComment = $event"
			@submit="submitComment"
			class="border-top"
		/>
	</div>
</template>

<script>
	import CommentForm from '../components/CommentForm'
	import chatMixin from '../mixins/chatMixin'
	import { mapState } from 'vuex'

	export default {
		props: {
			scrollContainerId: String,
			previousComments: Array
		},
		components: {
			CommentForm
		},
		mixins: [chatMixin],
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				isLoading: false,
				hosts: [],
			}
		},
		computed: {
			...mapState([
				'user',
			])
		},
		methods: {
			submitComment: function(e) {
				if (this.isLoading) return;

				axios
					.post(process.env.MIX_APP_URL + '/w/api/host/comments', {
						commentId: this.newCommentId,
						text: this.newComment
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
			}
		},
		created: function() {
			// I need to finish the code to pull XSRF cookie. 
			Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = decodeURIComponent(document.cookie.split('=')[1]);

			Echo.join('host.chat')
			    .here((users) => {
			        this.hosts = users;
			    })
			    .joining((user) => {
			    	this.hosts.push(user);
			    	console.log(user.name + ' has joined.');
			    })
			    .leaving((user) => {
			    	this.hosts = this.hosts.filter(host => host.id != user.id);
			        console.log(user.name + ' has left.');
			    })
			    .listen('HostCommentCreated', (comment) => {
			        this.$_chatMixin_publishComment(comment);
			    });
		},
		mounted: function() {
			this.$_chatMixin_publishComments(this.previousComments);
		},
		beforeDestroy: function() {
			Echo.leave('host.chat');
		}
	}
</script>