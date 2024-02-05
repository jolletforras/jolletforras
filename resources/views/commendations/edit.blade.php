@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Ajánló szerkesztése</h2>

			@include('errors.list')
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit" accept-charset="UTF-8">
				@include('commendations._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
	
@stop

@section('footer')
	@include('partials.add_tag_no_new_script')
@endsection