window.Echo.channel(channelName)
	.listen('BroadcastCommentCreated', event => {
		console.log(event);
});
