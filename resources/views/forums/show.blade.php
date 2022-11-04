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
	@include('comments._show', [
	'comments' => $comments,
    'commentable_type'	=>'Forum',
    'commentable_url'	=>'forum/'.$forum->id.'/'.$forum->slug,
    'commentable'	=>$forum
	] )
@endsection

