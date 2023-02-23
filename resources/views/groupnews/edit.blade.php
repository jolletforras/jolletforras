@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')

	<h2>Hírek szerkesztése</h2>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('hir')}}/{{$news->id}}/{{$news->slug}}/modosit" accept-charset="UTF-8">
				@include('groupnews._form', ['submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
@stop