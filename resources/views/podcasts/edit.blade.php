@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Podcast szerkesztése</h2>
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('podcast')}}/{{$podcast->id}}/{{$podcast->slug}}/modosit" accept-charset="UTF-8" enctype="multipart/form-data">
				@include('podcasts._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>

@stop