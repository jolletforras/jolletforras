@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Csoport témám szerkesztése</h2>

			@include('errors.list')
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit" accept-charset="UTF-8">
				@include('groupthemes._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
	
@stop