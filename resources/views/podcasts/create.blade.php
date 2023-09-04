@extends('layouts.app')

@section('content')
	<h2>Új podcast</h2>
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('podcast')}}/uj" accept-charset="UTF-8">
				@include('podcasts._form', ['submitButtonText'=>'Mentés'])
			</form>
		</div>
	</div>
@stop