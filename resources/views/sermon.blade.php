@extends('layouts.app')

@section('title', 'Second Online')

@section('content')
{{ $sermon->title }}
{{ $sermon->speaker->name }}
{{ $sermon->series->name }}
<a href="http://youtube.com/watch/{{ $sermon->youtube_id }}">Watch</a>
@endsection