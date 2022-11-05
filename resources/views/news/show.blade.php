@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $news->title }}</h2>
		</div>
        <div class="panel-body">
			@if (Auth::check() && Auth::user()->id==$news->user->id)
				<a href="{{url('iras')}}/{{$news->id}}/{{$news->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			{!! $news->body !!}
			@include('partials.author', ['author'=>'','obj'=>$news])
	    </div>
    </div>
@endsection

