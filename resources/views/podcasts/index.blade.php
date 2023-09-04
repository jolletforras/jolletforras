@extends('layouts.app')
@section('description', 'Az új világhangjai a Társadalmi Jóllét Portálon. Ki milyennek képzeli azt a bizonyos új világot.')
@section('url', 'https://tarsadalmijollet.hu/az-uj-vilag-hangjai')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/az-uj-vilag-hangjai" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-3">
			<h2>Az új világhangjai</h2><a href="#podcast_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
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
			<h3>{{ $podcast->title }}</h3>
			<br>
			<article>
				<div class="body"><iframe src="{{$podcast->url}}" height="100%" width="100%" frameborder="0" scrolling="no"></iframe></div>
				Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a>
			</article>

			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection

@section('footer')

@endsection