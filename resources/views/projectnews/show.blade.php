@extends('layouts.app')
@section('title'){{ $news->title }}@endsection
@section('description'){{ $news->meta_description }}@endsection
@section('url'){{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}" />@endsection
@section('image')@if(!empty($news->image)){{url('/')}}/images/posts/{{$news->image}}@else{{url('/images/jolletforras.png')}}@endif
@endsection

@section('content')
	<div class="inner_box">
		<h2>{{ $news->title }}</h2>
		@if ($news->project->isAdmin() || Auth::check() && Auth::user()->admin)
			<a href="{{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		{!! $news->body !!}
		@include('partials.author', ['author'=>'','obj'=>$news])
	</div>
@endsection

