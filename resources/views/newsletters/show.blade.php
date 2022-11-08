@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $newsletter->title }}</h2>
		</div>
        <div class="panel-body">
        	<p><b>{{ $newsletter->user->name }}, {{ $newsletter->updated_at }}</b></p>
			{!! $newsletter->body !!}
			@if (Auth::check() && Auth::user()->admin)
			<a href="{{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
@endsection

