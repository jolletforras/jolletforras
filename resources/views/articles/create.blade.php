@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Írás felvétele</h2>
	@include('articles._new_article_info', ['collapse'=>''])
	<div class="panel panel-default">
        <div class="panel-body">

			<form method="POST" action="{{url('iras')}}/uj" accept-charset="UTF-8">
				@include('articles._form')
			</form>
       </div>
	</div>	
@stop