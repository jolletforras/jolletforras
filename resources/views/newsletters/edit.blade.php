@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')

	<h2>Hírlevél szerkesztése</h2>
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}/modosit" accept-charset="UTF-8">
				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" maxlength="60" value="{{$newsletter->title}}" id="title">
				</div>

				<div class="form-group">
					<label for="title">Rövid leírás:</label>
					<textarea class="form-control" required="required" rows="20" name="short_description" cols="50">{{$newsletter->short_description}}</textarea>
				</div>

				<div class="form-group">
					<label for="title">Teljes hírlevél:</label>
					<textarea class="form-control" required="required" rows="20" name="body" cols="50">{{$newsletter->body}}</textarea>
				</div>

				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>
		</div>
	</div>
@stop