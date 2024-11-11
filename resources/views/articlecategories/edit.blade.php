@extends('layouts.app')

@section('content')
@include('partials.tinymce_just_link_js')
<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Témakör szerkesztése</h2>

		@include('errors.list')
	</div>
    <div class="panel-body">		
		<form method="POST" action="{{url('iras-temakor')}}/{{$category->id}}/{{$category->slug}}/modosit" accept-charset="UTF-8">
			@include('articlecategories._form')
			<div class="form-group">
				<input class="btn btn-primary" type="submit" value="Módosít">
			</div>
		</form>
	</div>
</div>
	
@stop
