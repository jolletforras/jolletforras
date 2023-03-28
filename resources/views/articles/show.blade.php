@extends('layouts.app')
@section('title'){{ $article->title }}@endsection
@section('description'){{ $article->meta_description }}@endsection
@section('url'){{url('iras')}}/{{$article->id}}/{{$article->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}" />@endsection
@section('image')@if(!empty($article->image)){{url('/')}}/images/posts/{{$article->image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection


@section('content')
	<div class="inner_box">
		<h2>{{ $article->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$article->user->id)
			<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		{!! $article->body !!}
		@include('partials.author', ['author'=>'','obj'=>$article])
    </div>
	@if(Auth::check())
		@include('comments._show', [
		'comments' => $comments,
		'commentable_type'	=>'Article',
		'commentable_url'	=>'iras/'.$article->id.'/'.$article->slug,
		'commentable'	=>$article
		] )
	@endif
@endsection

