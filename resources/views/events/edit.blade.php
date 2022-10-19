@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')

	<h2>Esemény szerkesztése</h2>
	<div class="panel panel-default">
		<div class="panel-body">

			<form method="POST" action="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" accept-charset="UTF-8">

				@csrf

				<?php $group_id = $event->group_id; ?>
				@include('events._form')

			</form>

		</div>
	</div>
@stop