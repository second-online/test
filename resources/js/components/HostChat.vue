<template>
	<div class="d-flex flex-column h-100">
		<!-- <span>{{ hosts.length }} hosts in here</span> -->
		<div class="flex-grow-1 overflow-y" style="background: #e8e8e8">
			<div
				style="padding: 10px 40px; word-break: break-all"
				v-for="comment in comments"
			>
				<span class="d-block"><b>{{ comment.user.name }}</b></span>
				<span class="d-block">{{ comment.text }}</span>
			</div>
		</div> 

<!-- 		<div
			class="d-flex align-items-center flex-shrink-0 comment-form"
		>
			<input-editable
				v-bind:value="newComment"
				v-on:input="newComment = $event"
				v-on:key-down-enter="submitComment"
			/>
		</div> -->
		
		<form class="d-flex align-items-center flex-shrink-0 comment-form">	
			<textarea
				id="comment-textarea"
				class="d-block w-100 p-0 border-0"
				placeholder="Write a comment.."
				v-on:input="resizeTextarea"
				v-model="newComment"
				v-on:keydown.enter="submitComment"
				rows="1"
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
				hosts: [],
			}
		},
		computed: {
			isUserAuthenticated: function() {
				return this.$store.getters.isUserAuthenticated;
			}
		},
		methods: {
			resizeTextarea: function() {
				// document.getElementById("comment-textarea").setAttribute('rows', '5');
				// return;
				const scrollHeight = document.getElementById("comment-textarea").scrollHeight;
				document.getElementById("comment-textarea").style.height = scrollHeight + 'px';
				console.log(document.getElementById("comment-textarea").scrollHeight)
			},
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

			// for (let i = 0; i < 10000; i++) {
			// 	const comment = {
			// 		text: 'this.newComment',
			// 		user: this.$store.state.user
			// 	};
			// 	this.comments.push(comment)
			// }
		},
		mounted: function() {
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
			        this.comments.push(comment);
			    });
		},
		beforeDestroy: function() {
			Echo.leave('host.chat');
		}
	}
</script>