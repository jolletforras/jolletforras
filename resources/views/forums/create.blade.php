@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Új téma</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" style="font-size: 18px">
				Mielőtt megnyitsz egy új témát, tedd fel magadnak a kérdést: Hogyan kapcsolódik ez a Portál céljához és alapértékeihez? Ha nem vagy biztos a dolgodban, inkább olvasd el újra a <a href="{{ url('/') }} " target="_blank">Nyitólapot</a> és
				a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a>, vagy kérj segítséget a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoporttól.<br>
				Az új fórumtémánál fogalmazd meg a címet és a leírást úgy, hogy minél közérthetőbb legyen. Tegyél fel kérdést, amire a választ keresed. Jelöld meg címkével a témakört, amihez tartozik a fórum. Ha menetközben érzékeled, hogy félreértették a hozzászólók
				a kérdésedet, pontosítsd azt.<br>
				Ha már úgy látod, hogy kaptál választ, írhatsz egy lezáró, összefoglaló hozzászólást, megköszönve a hozzászólók figyelmét.
			</div>
		</div>
	</div>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('forum')}}/uj" accept-charset="UTF-8">

				@csrf
				@include('forums._form', ['submitButtonText'=>'Mentés'])

			</form>
		</div>
	<div>
@stop