@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $news->title }}</h2>
		</div>
        <div class="panel-body">
        	<p><b>{{ $news->user->name }}, {{ $news->updated_at }}</b></p>
			{!! $news->body !!}
			@if (Auth::check() && Auth::user()->id==$news->user->id)
			<a href="{{url('iras')}}/{{$news->id}}/{{$news->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
@endsection

