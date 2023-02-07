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
	<div class="inner_box" style="margin-top:6px;">
		@include('events._list',['events'=>$events])
		@if(Auth::check() && $events_expired->isNotEmpty())
			<hr>
			<button href="#events_expired" data-toggle="collapse" class="btn btn-default"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Lejárt események</button>
			<div class="collapse" id="events_expired">
				<br>
				@include('events._list',['events'=>$events_expired])
			</div>
		@endif
	</div>
@endsection