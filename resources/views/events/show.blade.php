@extends('layouts.app')
@section('title'){{ $event->title }}@endsection
@section('description'){{ $event->meta_description }}@endsection
@section('url'){{url('esemeny')}}/{{$event->id}}/{{$event->slug}}@endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/esemenyek" />
@endsection

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $event->title }}</h2>@if($event->isGroupEvent())&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$event->group->id}}/{{$event->group->slug}}/esemenyek"> << {{$event->group->name}}</a>@endif
		</div>
		<div class="panel-body">
			{!! $event->body !!}
			@if ($has_access)
				<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			<br>
			@include('partials.author', ['author'=>'Eseményt felvette: ','obj'=>$event])
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