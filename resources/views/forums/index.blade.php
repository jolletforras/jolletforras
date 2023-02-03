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
		@include('forums._list')
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'forum'])
@endsection