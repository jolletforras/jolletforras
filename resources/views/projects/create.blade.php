@extends('layouts.app')

@section('content')
	<h2>Kezdeményezés felvétele</h2>
	
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">

			<form method="POST" action="{{url('kezdemenyezes')}}/uj" accept-charset="UTF-8">

				@csrf

				@include('projects._form')
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>

		</div>
	</div>
@stop

@section('footer')
	@include('projects._script')
	@include('partials.add_tag_script')
@endsection