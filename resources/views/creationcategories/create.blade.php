@extends('layouts.app')

@section('content')
	@include('partials.tinymce_just_link_js')
	<h2>Témakör felvétele</h2>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('alkotas-temakor')}}/uj" accept-charset="UTF-8">
				@include('creationcategories._form')
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>
			</form>
		</div>
	</div>
@stop
