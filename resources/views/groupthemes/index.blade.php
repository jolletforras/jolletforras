@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<?php $isAdmin = $group->isAdmin(); ?>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<div class="row">
			<div class="col-sm-4">
				@if ($page=="conversation" && $status=='closed')
					<h3>Lezárt beszélgetések</h3>
				@endif
				@if ($group->isActive() && ($page=="conversation" && $status!='closed' || $page=="announcement" && $isAdmin || $page=="knowledge"))
					<?php $url = ['conversation'=>'beszelgetes','announcement'=>'kozlemeny','knowledge'=>'tudastar']; ?>
					<a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/{{$url[$page]}}/uj" type="submit" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i>Új téma</a>
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
		<hr style="margin-top:2px;">
		@foreach ($forums as $forum)
			@if(isset($forum->user->id) && $forum->user->status==3)
				<h3><a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->title }}</a></h3>
				<p>
					<span class="author"><a href="{{ url('profil',$forum->user->id) }}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>,	{{ $forum->created_at }}</span>
					@if ($page=="conversation" && Auth::user()->id==$forum->user->id || $page=="announcement" && $isAdmin)
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{$forum->id}}/{{$forum->slug}}/modosit" class="btn btn-default btn-xs">módosít</a>
					@endif
					@if ($isAdmin)
						@if($forum->active)
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/lezar-beszelgetest/{{ $forum->id }}/{{$forum->slug}}" class="btn btn-default btn-xs">lezár</a>
						@else
						<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/megnyit-beszelgetest/{{ $forum->id }}/{{$forum->slug}}" class="btn btn-default btn-xs">megnyit</a>
						@endif
					@endif
				</p>
				@if(isset($forum->shorted_text))
					{!! str_replace("#...#","<a href='".url('csoport')."/".$group->id."/".$group->slug."/tema/".$forum->id."/".$forum->slug."'>... tovább</a>",$forum->shorted_text) !!}
				@else
					{!! preg_replace("/<img[^>]+\>/i", "",$forum->body) !!}
				@endif
				@include('partials.tags',['url'=>'csoport/'.$group->id.'/'.$group->slug.'/tema','obj'=>$forum])
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
				@if( $forum->counter>0)
					&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{ $forum->counter }} hozzászólás</a>
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