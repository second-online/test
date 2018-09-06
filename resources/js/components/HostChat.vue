<template>
	<div>
		<div class="comments-section">
			<span>{{ hosts.length }} hosts in here</span>
			<div v-for="comment in comments">
				<span class="comments-username">{{ comment.user.name }}</span>
				<span>{{ comment.text }}</span>
			</div>
			<!-- avoid double enter with a sending flag -->
			<form id="broadcast-comment-form" v-on:submit.prevent="submitHostComment">
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
				isLoading: false,
				hosts: []
			}
		},
		computed: {
			isUserDefined: function() {
				return (typeof this.$store.state.user.id != 'undefined') ? true : false;
			}
		},
		methods: {
			submitHostComment: function() {

				if (this.isLoading) { return; }

				if (this.isUserDefined) {
					const comment = {
						text: this.newComment,
						user: this.$store.state.user
					};

					this.comments.push(comment);
				}

				axios
					.post('http://second.test/w/api/host/comments', {
						text: this.newComment,
					})
					.then(response => {
						console.log('then');

						if (! this.isUserDefined) {
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
		created: function() {
			console.log('host chat created');
		},
		mounted: function() {
			console.log('host chat mounted');

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