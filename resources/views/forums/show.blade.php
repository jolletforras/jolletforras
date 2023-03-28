@extends('layouts.app')

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $forum->title }}</h2>
		</div>
        <div class="panel-body">
			@if (Auth::user()->id==$forum->user->id)
				<a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			{!! $forum->body !!}
			@include('partials.tags',['url'=>'forum','obj'=>$forum])
			@include('partials.author', ['author'=>'','obj'=>$forum])
	    </div>
    </div>
	@include('comments._show', [
	'comments' => $comments,
    'commentable_type'	=>'Forum',
    'commentable_url'	=>'forum/'.$forum->id.'/'.$forum->slug,
    'commentable'	=>$forum
	] )
@endsection

