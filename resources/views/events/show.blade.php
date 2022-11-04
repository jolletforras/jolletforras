@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $event->title }}</h2>
		</div>
		<div class="panel-body">
			{!! $event->body !!}
			@if ($has_access)
				<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			<br>
			<p><i>Eseményt felvette: @if(Auth::check() || $event->user->public)<a href="{{ url('profil',$event->user->id) }}/{{$event->user->slug}}">{{ $event->user->name }}</a>@else{{ $event->user->name }}@endif, {{ $event->created_at }}</i></p>
		</div>
	</div>
	@if(Auth::check())
		@include('comments._show', [
		'comments' => $comments,
		'commentable_type'	=>'Event',
		'commentable_url'	=>'esemeny/'.$event->id.'/'.$event->slug,
		'commentable'	=>$event
		] )
	@endif
@endsection