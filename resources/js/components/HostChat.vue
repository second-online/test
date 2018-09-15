<template>
	<div class="d-flex flex-column h-100">
		<!-- <span>{{ hosts.length }} hosts in here</span> -->
		<div class="flex-grow-1 overflow-y" style="background: #e8e8e8">
			<div
				style="padding: 15px 40px; word-break: break-all"
				v-for="comment in comments"
			>
				<span class="d-block"><b>{{ comment.user.name }}</b></span>
				<span class="d-block">{{ comment.text }}</span>
			</div>
		</div> 

		<form
			class="comment-form flex-shrink-0"
			v-on:keydown.enter="submitComment"
		>	
			<textarea
				class="d-block w-100 h-100 p-0 border-0"
				type="text"
				placeholder="Write a comment.."
				v-model="newComment">
			></textarea>
		</form>
	</div>
</template>

<script>
	export default {
		props: {
			previousComments: Array
		},
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				isLoading: false,
				hosts: []
			}
		},
		computed: {
			isUserAuthenticated: function() {
				return this.$store.getters.isUserAuthenticated;
			}
		},
		methods: {
			submitComment: function(e) {

				if (this.isLoading) { return; }

				if (! this.isUserAuthenticated) { return; }

				const localCommentId = this.newCommentId++;

				axios
					.post(process.env.MIX_APP_URL + '/w/api/host/comments', {
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

				e.preventDefault();
			}
		},
		created: function() {
			this.comments.push(...this.previousComments);
		},
		mounted: function() {

			window.Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = decodeURIComponent(document.cookie.split('=')[1]);

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
			        this.comments.push(comment);
			    });
		}
	}
</script>