@extends('layouts.app')

@section('title', $broadcast->name)

@section('content')

<div id="broadcast-page">
	<div class="broadcast-video">
	</div>
	<div class="comments-section">
		<div class="broadcast-comments">
			<broadcast-comment
				v-bind:comment="{{ $broadcast->comments->first() }}"
			></broadcast-comment>
		</div>
		<form id="broadcast-comment-form" v-on:submit.prevent="submitBroadcastComment">
			@csrf
			<input type="text" placeholder="Write a comment..">
		</form>
	</div>
</div>

@endsection

@section('footer')
	<script type="text/javascript">
		var channelName = 'wed-8am-broadcast';
	</script>
	<script src="{{ asset('js/broadcast.js') }}"></script>
@endsection