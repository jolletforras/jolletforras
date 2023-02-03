@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Hírlevelek</h2>
		</div>
		@if (Auth::check() && Auth::user()->admin)
		<div class="col-sm-3 text-right">
			<a href="#newsletter_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('hirlevel')}}/uj" type="submit" class="btn btn-default">Új Hírlevél</a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="newsletter_info" style="font-size: 18px">
				Ezen az oldalon a hírlevelek találhatóak.
			</div>
		</div>
	</div>
	@include('newsletters._list')
@endsection