@extends('layouts.app')

@section('content')
	<h2>Új ajánló</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" style="font-size: 18px">
				Mielőtt megnyitsz egy új ajánlót, tedd fel magadnak a kérdést: Hogyan kapcsolódik ez a Jóllét Forrás oldal céljához és alapértékeihez? Ha nem vagy biztos a dolgodban, inkább olvasd el újra a <a href="{{ url('/') }} " target="_blank">Nyitólapot</a> és
				a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a>, vagy kérj segítséget a <a href="{{ url('/') }}/csoport/1/jollet-forras-mag" target="_blank">Jóllét Forrás Mag</a> csoporttól.<br>
			</div>
		</div>
	</div>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('ajanlo')}}/uj" accept-charset="UTF-8">
				@include('commendations._form', ['submitButtonText'=>'Mentés'])
			</form>
		</div>
	</div>
@stop

@section('footer')
	@include('partials.add_tag_no_new_script')
@endsection