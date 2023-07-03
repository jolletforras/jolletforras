@extends('layouts.app')

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-3">
			<h2>Ajánló</h2>
		</div>
		<div class="col-sm-3" style="padding-top:4px;">
		</div>
		<div class="col-sm-6 text-right">
			@if (Auth::check())
			<a href="#commendation_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('ajanlo')}}/uj" type="submit" class="btn btn-default">Új ajánló</a>
			@endif
		</div>
	</div>
	<div class="row narrow-page">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="commendation_info" style="font-size: 18px">
				Itt lehetőséged mások figyelmébe ajánlani valamit.<br>
				Mielőtt megnyitsz egy új ajánlót, tedd fel magadnak a kérdést: Hogyan kapcsolódik ez a Portál céljához és alapértékeihez? Ha nem vagy biztos a dolgodban, inkább olvasd el újra a <a href="{{ url('/') }} " target="_blank">Nyitólapot</a> és
				a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a>, vagy kérj segítséget a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoporttól.<br>
			</div>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('commendations._list')
	</div>
@endsection

