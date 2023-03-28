@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<div class="row">
			<div class="col-sm-4">
				@if ($page=="conversation")
					@if($status=='closed')
						<h3>Lezárt beszélgetések</h3>
					@else
						<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/tema/uj" type="submit" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i>Új téma</a>
					@endif
				@endif
			</div>
			<div class="col-sm-3" style="padding-top:4px;">
				@if ($page!="announcement")
				<select id="tag" name="tag" class="form-control">
					@foreach($tags as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
				@endif
			</div>
			<div class="col-sm-5 text-right">
				@if ($page=="conversation" && $status=='active')
					<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/lezart-beszelgetesek" class="right">Lezárt beszélgetések</a>
				@endif
			</div>
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
				@include('partials.tags',['url'=>'csoport/'.$group->id.'/'.$group->slug.'/tema','obj'=>$forum])
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
				@if( $forum->counter>0)
					&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->counter }} hozzászolás</a>
				@endif
				<hr/>
			@endif
		@endforeach
	</div>
@endsection

@section('footer')
	@if ($page!="announcement")
		@include('partials.search_tag_script',['url'=>'csoport/'.$group->id.'/'.$group->slug.'/tema'])
	@endif
@endsection