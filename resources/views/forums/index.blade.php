@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-3">
			<h2>Fórum</h2>
		</div>
		<div class="col-sm-3" style="padding-top:4px;">
			<select id="tag" name="tag" class="form-control">
				@foreach($tags as $key => $val)
					<option value="{{ $key }}">{{ $val }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-6 text-right">
			<a href="#forum_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('forum')}}/uj" type="submit" class="btn btn-default">Új téma</a>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="forum_info" style="font-size: 18px">
				Van egy témád, amiben szeretnéd megkérdezni a többiek véleményét? Itt hozhatsz létre egy új témát.<br>
				Mielőtt megnyitsz egy új témát, tedd fel magadnak a kérdést: Hogyan kapcsolódik ez a Portál céljához és alapértékeihez? Ha nem vagy biztos a dolgodban, inkább olvasd el újra a <a href="{{ url('/') }} " target="_blank">Nyitólapot</a> és
				a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a>, vagy kérj segítséget a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoporttól.<br>
			</div>
		</div>
	</div>
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		@for ($i = 0; $i < $num=$forums->count(); $i++)
			<?php $forum = $forums[$i]; ?>
			@if(isset($forum->user->id))
				<h3><a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}">{{ $forum->title }}</a></h3>
				<p>
					<a href="{{ url('profil',$forum->user->id) }}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>,	{{ $forum->updated_at }}
					@if (Auth::user()->id==$forum->user->id)
						<a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit">módosít</a>
					@endif
				</p>
				{!! $forum->body !!}
				@include('forums.tags')
				<a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
				@if( $forum->counter>0)
					&nbsp;&nbsp;<a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}">{{ $forum->counter }} hozzászolás</a>
				@endif
				@if($i!=$num-1)<hr>@endif
			@endif
		@endfor
	</div>
@endsection

@section('footer')
	<script>
		var tags = {
		@foreach ($tags_slug as $id => $slug)
		{{$id}}:"{{$slug}}",
		@endforeach
		};

		$('#tag').select2({
			placeholder: 'Keresés címke szerint',
			tags: false
		});

		$("#tag").change(function () {
			var id= $("#tag").val();
			location.href="{{ url('forum')}}/cimke/"+id+"/"+tags[id];
		});
	</script>
@endsection