@extends('layouts.app')

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $project->title }}</h2>
		</div>
        <div class="panel-body">
        	<p><b>{{ $project->user->name }}, {{ $project->updated_at }}</b></p>
			<p>{{ $project->body }}</p>
			<p>{{ $project->looking_for }}</p>
			@include('projects._members')
			@include('projects._tags')
			@if (Auth::user()->id==$project->user->id)
			<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
	@include('comments._show', [
	'comments' => $comments,
	'commentable_type'	=>'Project',
	'commentable_url'	=>'kezdemenyezes/'.$project->id.'/'.$project->slug,
	'commentable'		=>$project
	] )
@endsection