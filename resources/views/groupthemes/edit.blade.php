@extends('layouts.app')

@section('content')
	@include('partials.tinymce_just_link_js')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Csoport témám szerkesztése</h2>

			@include('errors.list')
		</div>
		<div class="panel-body">

			<form method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{$forum->id}}/{{$forum->slug}}/modosit" accept-charset="UTF-8">
				@include('groupthemes._form', ['operation'=>'edit','submitButtonText'=>'Módosít'])
			</form>
		</div>
	</div>
	
@stop