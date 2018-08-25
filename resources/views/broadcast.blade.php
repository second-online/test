@extends('layouts.app')

@section('title', $broadcast->name)

@section('content')

<div id="broadcast-page">
	<div class="broadcast-video">
	</div>
	<div class="comments-section">
		<div class="broadcast-comments">
		
		</div>
		<form id="broadcast-comment-form" method="POST" action="{{ route('broadcasts.comments.create', ['broadcasts' => $broadcast->id]) }}">
			@csrf
			<input type="text" name="comment" placeholder="Write a comment..">
		</form>
	</div>
</div>

@endsection

@section('footer')
	<script type="text/javascript" defer>
		var channelName = 'wed-8am-broadcast';
	</script>
	<script src="{{ asset('js/broadcast.js') }}" defer></script>
@endsection