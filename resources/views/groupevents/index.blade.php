@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
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
		@for ($i = 0; $i < $num=$events->count(); $i++)
			<?php $event = $events[$i]; ?>
		<h3><a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->title }}</a></h3>
			@if ($event->isEditor() || $group->isAdmin())
		<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" class="btn btn-default">módosít</a>
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