@extends('layouts.app')
@section('description'){{$user->name}} írásai a Jóllét Forrás oldalon. Milyen legyen az új világunk? Olvasd el a Jóllét Forrás oldal tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!@endsection
@section('url')https://jolletforras.hu/profil/{{$user->id}}/{{$user->slug}}/irasok @endsection
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/profil/{{$user->id}}/{{$user->slug}}/irasok" />
@endsection

@section('content')
	@include('profiles.partials.profile_menu')
	@if ($user->myProfile())
		@include('articles._new_article_info', ['collapse'=>' collapse'])
	@endif

	<div class="row" style="margin-top: 6px;">
		@include('articles._list')
	</div>
@endsection