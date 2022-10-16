@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $article->title }}</h2>
		</div>
        <div class="panel-body">
        	<p><b>{{ $article->user->name }}, {{ $article->updated_at }}</b></p>
			{!! $article->body !!}
			@if (Auth::check() && Auth::user()->id==$article->user->id)
			<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
@endsection

