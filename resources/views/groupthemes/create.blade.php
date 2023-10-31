@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>{{$title}}</h2>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/uj" accept-charset="UTF-8">
				@include('groupthemes._form', ['submitButtonText'=>'Mentés'])
			</form>
		</div>
	</div>
@stop