@extends('layouts.app')
@section('description', 'Írások a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Olvasd el a Portál tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/irasok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/irasok" /> @endsection
@section('image'){{ url('/images/irasok.jpeg')}}@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Írások</h2>
		</div>
		@if (Auth::check())
		<div class="col-sm-3 text-right">
			<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új írás</a>
		</div>
		@endif
	</div>
	@include('articles._new_article_info', ['collapse'=>' collapse'])
	<div class="row">
		@include('articles._list')
	</div>
@endsection