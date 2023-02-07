@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="inner_box" style="margin-top:6px;">
		<div class="row">
			<div class="col-sm-9"></div>
			<div class="col-sm-3 text-right">
				@if (Auth::check())
					<a href="#create_events_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/esemeny/uj" type="submit" class="btn btn-default">Esemény felvétele</a>
				@endif
			</div>
		</div>
		@include('events._create_events_info')
		<hr style="margin-top:2px;">
		@include('groupevents._list',['events'=>$events,'actual'=>True])
		@if(Auth::check() && $events_expired->isNotEmpty())
			<hr>
			<button href="#events_expired" data-toggle="collapse" class="btn btn-default"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Lejárt események</button>
			<div class="collapse" id="events_expired">
				<br>
				@include('groupevents._list',['events'=>$events_expired,'actual'=>False])
			</div>
		@endif
	</div>
@endsection