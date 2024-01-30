@extends('layouts.app')

@section('content')
@include('partials.tinymce_just_link_js')
<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Csoport szerkesztése</h2>

		@include('errors.list')
	</div>
    <div class="panel-body">		
		<form method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/modosit" accept-charset="UTF-8">
			@include('groups._form')
			<div class="form-group">
				<input class="btn btn-primary" type="submit" value="Módosít">
			</div>
		</form>
	</div>
</div>
	
@stop

@section('footer')
	@include('partials.add_tag_script')
	@include('groups._add_local_data_script')
@endsection