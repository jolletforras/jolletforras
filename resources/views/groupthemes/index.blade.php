@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		<div class="row">
			<div class="col-sm-9"></div>
			<div class="col-sm-3 text-right">
				@if (Auth::check())
					<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/tema/uj" type="submit" class="btn btn-default right"><i class="fa fa-plus" aria-hidden="true"></i>Új téma</a>
				@endif
			</div>
		</div>
		<hr style="margin-top:2px;">
		@foreach ($forums as $forum)
			@if(isset($forum->user->id))
				<h3><a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->title }}</a></h3>
				<p>
					Felvette: <a href="{{ url('profil',$forum->user->id) }}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>,	{{ $forum->updated_at }}
					@if (Auth::user()->id==$forum->user->id)
						<a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit">módosít</a>
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