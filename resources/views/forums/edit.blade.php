@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Fórum témám szerkesztése</h2>

			@include('errors.list')
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit" accept-charset="UTF-8">
				@include('forums._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
	
@stop