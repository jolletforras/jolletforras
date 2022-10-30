@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $forum->title }}</h2>
		</div>
        <div class="panel-body">
        	<p><b>{{ $forum->user->name }}, {{ $forum->updated_at }}</b></p>	
			{!! $forum->body !!}
			@include('forums.tags')
			@if (Auth::user()->id==$forum->user->id)
			<a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
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
		<textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Ide írva szólhatsz hozzá"></textarea>
	</div>
	<div class="form-group">
		<button type="button" onclick="save()">Mentés</button>
	</div>
	@include('partials.comment_script', [
        'commentable_type'	=>'Forum',
        'commentable_url'	=>'forum/'.$forum->id.'/'.$forum->slug,
        'commentable_id'	=>$forum->id,
        'name'				=>$forum->user->name,
        'email'				=>$forum->user->email
    ] )
@endsection

