@extends('layouts.app')

@section('content')
	<h2>Új alkotás</h2>
	@include('creations._new_creation_info', ['collapse'=>''])
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('alkotas')}}/uj" accept-charset="UTF-8" enctype="multipart/form-data">
				@include('creations._form', ['submitButtonText'=>'Mentés'])
			</form>
		</div>
	</div>
@stop

@section('footer')
	@include('creations._script')
@endsection