@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
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
	<div class="form-group">
		@if(!$comments->isEmpty())<b>Hozzászólások:</b><br/>@endif
		<hr>
		<div class="comments">
			@foreach ($comments as $comment)
				<b>{{ $comment->commenter->name }}</b>, <b>{{ $comment->updated_at }}</b> <br/>
				{{ $comment->body }}<br/>
				<hr>
			@endforeach
		</div>
	</div>
	<div class="form-group">
		<textarea class="form-control" rows="4" name="comment" placeholder="Ide írva szólhatsz hozzá"></textarea>
	</div>
	<div class="form-group">
		<button type="button" onclick="save()">Mentés</button>
	</div>
	@include('partials.comment_script', [
        'commentable_type'	=>'Project',
        'commentable_url'	=>'kezdemenyezes/'.$project->id.'/'.$project->slug,
        'commentable_id'	=>$project->id,
        'name'				=>$project->user->name,
        'email'				=>$project->user->email
    ] )
@endsection