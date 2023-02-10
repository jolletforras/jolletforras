@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Hírlevél felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="newsletter_info" style="font-size: 18px">
				Ezen az oldalon a hírlevelek találhatóak.<br>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
        <div class="panel-body">

			<form method="POST" action="{{url('hirlevel')}}/uj" accept-charset="UTF-8">

				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" maxlength="60" value="" id="title">
				</div>

				<div class="form-group">
					<textarea class="form-control" required="required" rows="20" name="body" cols="50"></textarea>
				</div>

				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>
       </div>
	</div>	
@stop