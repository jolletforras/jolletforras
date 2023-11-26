@extends('layouts.app')
@section('description'){{$user->name}} írásai a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Olvasd el a Portál tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!@endsection
@section('url')https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/irasok @endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/irasok" />
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