@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')

	<h2>Írás szerkesztése</h2>
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" accept-charset="UTF-8">
				@include('articles._form')
			</form>
		</div>
	</div>
@stop

@section('footer')
	@include('partials.add_tag_no_new_script')
@endsection