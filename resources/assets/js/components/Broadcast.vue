<template>
	<div id="broadcast-page">
		<div class="broadcast-video">
			<span class="back" v-on:click="goBack">back</span>
			<Youtube id="44"></Youtube>
		</div>
		<div class="comments-section">
			<div v-for="comment in broadcast.comments">
				<span class="comments-username">{{ comment.user.name }}</span>
				<span>{{ comment.text }}</span>
			</div>
			<form id="broadcast-comment-form" v-on:submit.prevent="submitBroadcastComment">
				<input type="text" placeholder="Write a comment.." v-model="newComment">
			</form>
		</div>
	</div>
</template>

<script>
	import Youtube from '../components/Youtube'

	export default {
		components: {
			Youtube
		},
		data: function() {
			return {
				user: { 
					id: '10000',
					name: 'Alex Lacayo'
				},
				newComment: '',
				broadcast: []
			}
		},
		mounted() {
			// whats created vs mounted? 
			console.log('broadcast page mounted');

			axios
				.get('http://second.test/w/api/broadcasts/' + this.$route.params.id)
				.then(response => {
					this.broadcast = response.data.broadcast;
					console.log(response.data.broadcast);

					Echo.channel('broadcast-' + this.broadcast.id)
						.listen('BroadcastCommentCreated', data => {
							this.broadcast.comments.push(data)
					});
				});
		},
		methods: {
			submitBroadcastComment: function(e) {
				var comment = {
					text: this.newComment,
					user: this.user
				};

				this.broadcast.comments.push(comment);

				axios
					.post('http://second.test/w/api/broadcasts/' + this.$route.params.id + '/comments', {
						text: this.newComment,
					})
					.then(response => {
						console.log(response.data);
					});

				this.newComment = '';
			},
		    goBack () {
		    	this.leaveChannels();

				window.history.length > 1
					? this.$router.go(-1)
					: this.$router.push('/');
		    },
		    leaveChannels() {
		    	Echo.leave('broadcast-' + this.broadcast.id);
		    }
		}
	}
</script>