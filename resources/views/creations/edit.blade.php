@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Alkotás szerkesztése</h2>

			@include('errors.list')
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}/modosit" accept-charset="UTF-8" enctype="multipart/form-data">
				@include('creations._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
@stop
