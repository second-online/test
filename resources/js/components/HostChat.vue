<template>
	<div>
		<div class="comments-section2">
			<span>{{ hosts.length }} hosts in here</span>
			<div v-for="comment in comments">
				<span class="comments-username">{{ comment.user.name }}</span>
				<span>{{ comment.text }}</span>
			</div>
			<!-- avoid double enter with a sending flag -->
			<form id="host-comment-form" v-on:submit.prevent="submitComment">
				<input type="text" placeholder="Write a comment.." v-model="newComment">
			</form>
		</div>
	</div>
</template>

<script>
	export default {
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
			submitComment: function() {
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
			}
		},
		created: function() {
			console.log('host chat created');
		},
		mounted: function() {
			console.log('host chat mounted');

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