<template>
	<div class="d-flex flex-column flex-grow-1 mh-0">
		<div
			:id="scrollContainerId"
			class="d-flex flex-column flex-grow-1 border-bottom overflow-y"
		>	
			<div class="pt-36">
				<div
					v-for="(comment, index) in comments"
					:key="comment.id"
					:id="'comment-' + comment.id"
					class="d-flex px-30 px-md-40 pb-36 flex-shrink-0"
				>	
					<img
						:src="fakeUser(index).profile_picture"
						class="profile-picture mt-2 mr-24 flex-shrink-0"
					>
					<div class="flex-grow-1">
						<div class="d-flex align-items-center justify-content-between">
							<span class="mb-4 font-weight-bold">{{ fakeUser(index).name }}</span>
							<!-- <span class="small" style="color: #bbb">{{ timeAgo(comment.created_at) }}</span> -->
						</div>
						<div>
							<span>{{ comment.text }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<comment-form
			:value="newComment"
			@input="newComment = $event"
			@submit="submitComment"
		/>
	</div>
</template>

<script>
	import CommentForm from '../components/CommentForm'
	import chatMixin from '../mixins/chatMixin'
	import helperMixin from '../mixins/helperMixin'
	import { mapState } from 'vuex'

	export default {
		components: {
			CommentForm
		},
		mixins: [
			chatMixin,
			helperMixin
		],
		props: {
			scrollContainerId: String,
			previousComments: Array
		},
		data: function() {
			return {
				comments: [],
				newComment: '',
				newCommentId: 1,
				cachedComment: '',
				showLoadMore: true,
				isLoading: false,
				hosts: [],
				users: [
					{
						id: 10,
						name: 'Zheyna Rynzhuk',
						profile_picture: 'https://cdn.dribbble.com/users/501822/avatars/normal/7ce826e27de30a8399175a9eb0284f5a.jpg?1453326105'
					},
					{
						id: 11,
						name: 'Giga Tamarashvili',
						profile_picture: 'https://cdn.dribbble.com/users/952958/avatars/normal/0101aa0aecfb782643de2075265cf6ac.png?1484162300'
					},
					{
						id: 12,
						name: 'Eric Hoffman',
						profile_picture: 'https://cdn.dribbble.com/users/1061799/avatars/normal/f170b323a5dfbd05cd1cafe6bcebcd03.jpg?1487686545'
					},
					{
						id: 13,
						name: 'Parham Marandi',
						profile_picture: 'https://cdn.dribbble.com/users/1061799/avatars/normal/f170b323a5dfbd05cd1cafe6bcebcd03.jpg?1487686545'
					}, {
						id: 14,
						name: 'Martin Ranelli',
						profile_picture: 'https://cdn.dribbble.com/users/2407/avatars/normal/92459ee3572492f7e2d7f3923a5432ac.jpg?1417755918'
					},
					{
						id: 15,
						name: 'Christopher Reath',
						profile_picture: 'https://cdn.dribbble.com/users/237483/avatars/normal/28f588cf80349d9c9c54c02d473060a4.png?1528765233'
					}
				]
			}
		},
		computed: {
			...mapState([
				'user',
			]),
			maxId: function() {
				return this.comments[0].id;
			},
			commentCount: function() {
				return this.comments.length;
			}
		},
		watch: {
			previousComments: function() {
				this.$_chatMixin_publishComments(this.previousComments);
			},
			hosts: function() {
				this.$emit('hosts-update', this.hosts);
			}
		},
		methods: {
			fakeUser: function(index) {
				return this.users[Math.floor((Math.random() * this.users.length))];
			},
			submitComment: function(e) {
				if (this.isLoading) { return; }

				if (this.newComment.length < 1) { return; }

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
			},
			loadMore: function() {
				axios
					.get(process.env.MIX_APP_URL + '/w/api/host/comments?maxid=' + this.maxId)
					.then(response => {
						if (response.data.comments.length < response.data.limit) {
							this.showLoadMore = false;
						}

						this.comments.unshift(...response.data.comments);
					})
					.catch(error => {

					})
					.then(() => {
						 
					});
			},
			timeAgo: function(timestamp) {
				return Moment.utc(timestamp)
					.fromNow(true);
			}
		},
		created: function() {
			
			console.log(this.$_helperMixin_getCookie('the-cookie'));

			Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = this.$_helperMixin_getXSRFCookie();

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
		beforeDestroy: function() {
			Echo.leave('host.chat');
		}
	}
</script>