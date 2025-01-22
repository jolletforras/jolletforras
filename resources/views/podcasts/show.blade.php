@extends('layouts.app')
@section('title'){{ $podcast->title }}@endsection
@section('description'){{ $podcast->meta_description }}@endsection
@section('url'){{url('az-uj-vilag-hangjai')}}/{{$podcast->id}}/{{$podcast->slug}}@endsection
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/az-uj-vilag-hangjai" />
@endsection

@section('content')
	<div class="inner_box">
		<h2>{{ $podcast->title }}</h2>
		@if (Auth::check() && Auth::user()->admin)
			<a href="{{url('podcast')}}/{{$podcast->id}}/{{$podcast->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		<p style="text-align: center;"><img src="{{ url('/images/podcasts') }}/{{ $podcast->id}}.jpg" style="max-width: 50%;max-height: 400px;"></p>
		<p>{{ $podcast->body }}</p>
		<audio controls>
			<source src="{{url('/audio/podcasts')}}/{{$podcast->url}}" type="audio/mpeg">
		</audio>
		<br>
		<?php
			$event = $podcast->event;
			$group = $podcast->group;
		?>
		@if(isset($event))
		Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a><br>
		@endif
		@if(isset($group))
			Kapcsolódó csoport: <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}" target="_blank">{{ $group->name }}</a><br>
		@endif
	</div>
@endsection

