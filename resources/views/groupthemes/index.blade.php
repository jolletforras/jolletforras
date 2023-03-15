@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		<div class="row">
			@if ($page=="conversation")
			<div class="col-sm-6">
				@if (Auth::check())
					<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/tema/uj" type="submit" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i>
						@if ($page=="conversation")Új téma @else Új közlemény @endif
					</a>
				@endif
			</div>
			<div class="col-sm-6 text-right">
					<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/lezart-beszelgetesek" class="right">Lezárt beszélgetések</a>
			</div>
			@endif
			@if ($page=="closed-conversation")<div class="col-sm-6"><h3>Lezárt beszélgetések</h3></div>@endif
		</div>
		@if ($page!="announcement")
		<hr style="margin-top:2px;">
		@endif
		@foreach ($forums as $forum)
			@if(isset($forum->user->id))
				<h3><a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->title }}</a></h3>
				<p>
					Felvette: <a href="{{ url('profil',$forum->user->id) }}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>,	{{ $forum->updated_at }}
					@if (Auth::user()->id==$forum->user->id)
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{$forum->id}}/{{$forum->slug}}/modosit" class="btn btn-default btn-xs">módosít</a>
					@endif
					@if ($group->isAdmin())
						@if($forum->active)
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/lezar-beszelgetest/{{ $forum->id }}/{{$forum->slug}}" class="btn btn-default btn-xs">lezár</a>
						@else
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/megnyit-beszelgetest/{{ $forum->id }}/{{$forum->slug}}" class="btn btn-default btn-xs">megnyit</a>
						@endif
					@endif
				</p>
				{!! $forum->body !!}
				<br>
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
				@if( $forum->counter>0)
					&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->counter }} hozzászolás</a>
				@endif
				<hr/>
			@endif
		@endforeach
	</div>
@endsection