@extends('layouts.app')
@section('description', 'Események a Társadalmi Jóllét Portálon. Programok, fórumok, lehetőségek, amelyek a társadalmi jóllét megvalósítását segítik. Nézz szét, regisztrálj, kapcsolódj!')
@section('url', 'https://tarsadalmijollet.hu/esemenyek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/esemenyek" />
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Események</h2><a href="#events_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		<div class="col-sm-3 text-right">
			@if (Auth::check())
				<a href="#create_events_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('esemeny')}}/uj" type="submit" class="btn btn-default">Esemény felvétele</a>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="events_info" style="font-size: 18px">
				Ezen az oldalon találod a regisztrált tagok és a csoportok nyilvános eseményeit.
			</div>
		</div>
	</div>
	@include('events._create_events_info')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
	@for ($i = 0; $i < $num=$events->count(); $i++)
		<?php $event = $events[$i]; ?>
		<h3><a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->title }}</a></h3>
		@if(isset($event->group))
			@if (Auth::check())
		<i>/A(z) <b><a href="{{ url('csoport',$event->group->id) }}/{{$event->group->slug}}">{{ $event->group->name }}</a></b> csoport eseménye/</i><br>
			@elseif ($event->group->public)
		<i>/A(z) <b>{{$event->group->name}}</b> csoport eseménye/</i><br>
			@endif
		@endif
		@if (Auth::check())
			@if($event->isEditor())
		<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" class="btn btn-default">módosít</a>
			@endif
		@endif
		<article>
			<div class="body">{!! $event->body !!}</div>
		</article>
		@if (Auth::check())
		<a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
			@if( $event->counter>0)
		&nbsp;&nbsp;<a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->counter }} hozzászolás</a>
			@endif
		@endif
		@if($i!=$num-1)<hr>@endif
	@endfor
	</div>
@endsection