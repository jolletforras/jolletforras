@extends('layouts.app')
@section('description', 'Az új világhangjai a Társadalmi Jóllét Portálon. Ki milyennek képzeli azt a bizonyos új világot.')
@section('url', 'https://tarsadalmijollet.hu/az-uj-vilag-hangjai')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/az-uj-vilag-hangjai" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-6">
			<h2>Az új világhangjai</h2><a href="#podcast_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		<div class="col-sm-6 text-right">
			@if (Auth::check() && Auth::user()->admin)
				<a href="{{url('podcast')}}/uj" type="submit" class="btn btn-default">Új podcast</a>
			@endif
		</div>
	</div>
	<div class="row narrow-page">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="podcast_info" style="font-size: 18px">
				Ezen az oldalon találhatod "az új világhangjai" podcast sorozat beszélgetéseit.
			</div>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@for ($i = 0; $i < $num=$podcasts->count(); $i++)
			<?php
				$podcast = $podcasts[$i];
				$event = $podcast->event;
			?>
			<h3>{{ $podcast->title }} </h3>
			@if (Auth::check() && Auth::user()->admin)
    			<a href="{{url('podcast')}}/{{$podcast->id}}/{{$podcast->slug}}/modosit">módosít</a>
			@endif
			<div class="body">
				<iframe class="podcast-iframe" src="{{$podcast->url}}" style="height:100%;width:100%;" frameborder="0" scrolling="no"></iframe>
				Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a><br>
			</div>
			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection

@section('footer')

@endsection