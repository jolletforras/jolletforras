@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Tudnivaló felvétele</h2>
	<div class="panel panel-default">
        <div class="panel-body">

			<form method="POST" action="{{url('tudnivalo')}}/uj" accept-charset="UTF-8">

				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" maxlength="60" value="" id="title">
				</div>

				<div class="form-group">
					<label for="meta_description">Meta leírás:</label>
					<a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
					<div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. facebook) megosztásra kerül az írás.</div>
					<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" id="meta_description">
				</div>

				<div class="form-group">
					<label for="title">Rövid leírás:</label>
					<textarea class="form-control" required="required" rows="20" name="short_description" cols="50"></textarea>
				</div>

				<div class="form-group">
					<label for="title">Teljes leírás:</label>
					<textarea class="form-control" required="required" rows="20" name="body" cols="50"></textarea>
				</div>

				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>
       </div>
	</div>	
@stop