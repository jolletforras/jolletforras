@extends('layouts.app')
@section('description', 'Írások a Jóllét Forrás oldalon. Milyen legyen az új világunk? Olvasd el a Jóllét Forrás oldal tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!')
@section('url', 'https://jolletforras.hu/irasok')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/irasok" /> @endsection
@section('image'){{ url('/images/irasok.jpeg')}}@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Írás csoportjaid</h2>
		</div>
		@if (Auth::check())
			<div class="col-sm-3 text-right">
				<a href="{{url('iras-csoportok')}}/uj" type="submit" class="btn btn-default">Új írás csoport</a>
			</div>
		@endif
	</div>
	<div class="row">
		@foreach ($categories as $category)
			<div class="col-sm-12">
				{{$category->title}}
			</div>
		@endforeach
	</div>
@endsection