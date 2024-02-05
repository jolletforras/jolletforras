@extends('layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Kezdeményezés szerkesztése</h2>
		
		@include('errors.list')
	</div>
    <div class="panel-body">		

		<form method="POST" action="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit" accept-charset="UTF-8">

			@csrf

			@include('projects._form')
			<div class="form-group">
				<input class="btn btn-primary" type="submit" value="Módosít">
				@if(Auth::user()->id == $project->user->id)
				<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/torol" type="submit" class="btn btn-default">Törlöm a kezdeményezést</a>
				@endif
			</div>

		</form>

	</div>
</div>
	
@stop

@section('footer')
	@include('projects._script')
	@include('partials.add_tag_no_new_script')
@endsection