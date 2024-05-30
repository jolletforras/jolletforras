@extends('layouts.app')
@section('description', 'Írások a Jóllét Forrás oldalon. Milyen legyen az új világunk? Olvasd el a Jóllét Forrás oldal tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!')
@section('url', 'https://jolletforras.hu/irasok')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/irasok" /> @endsection
@section('image'){{ url('/images/irasok.jpeg')}}@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Írások @if(isset($group))- {{$group->name}}@endif</h2>
		</div>
		@if (Auth::check())
		<div class="col-sm-3 text-right">
			<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új írás</a>
		</div>
		@endif
	</div>
	@guest
	<div class="inner_box">
			Itt a Jóllét Forrás oldal tagjainak saját írásait találod. Ez lehet gondolat, tapasztalat-megosztás, kutatási összefoglaló.<br>
			Arról, hogyan hozhatsz létre új írást és hogyan tudsz mások írásaihoz hozzászólni, <a href="{{url('tudnivalo')}}/11/irasok">ITT</a> találsz részletes tudnivalókat.<br>
			<br>
			Szeretnél értesülni új írásokról?<br>
			- Ha van kedved, regisztrálj a Jóllét Forrás oldalon és lépj be ebbe a térbe. Kezdd <a href="{{url('register')}}">ITT</a>.<br>
			- Ha még csak távolabbról ismerkednél, iratkozz fel a hírlevelünkre <a href="https://forms.gle/S18g4L3TAPC9ZMe99">ITT</a>.<br>
	</div>
	@endguest
	@include('articles._new_article_info', ['collapse'=>' collapse'])
	<div class="row">
		@include('articles._list')
	</div>
@endsection