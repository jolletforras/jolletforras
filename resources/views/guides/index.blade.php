@extends('layouts.app')
@section('description', 'Minden, amit tudni érdemes és szükséges a Társadalmi Jóllét Portál működéséről és lehetőségeiről.')
@section('url', 'https://tarsadalmijollet.hu/tudnivalok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tudnivalok" />@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Tudnivalók</h2>
		</div>
		@if (Auth::check() && Auth::user()->admin)
		<div class="col-sm-3 text-right">
			<a href="{{url('tudnivalo')}}/uj" type="submit" class="btn btn-default">Új Tudnivaló</a>
		</div>
		@endif
	</div>
	<div class="row">
		@include('guides._list')
	</div>
@endsection