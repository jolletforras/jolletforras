@extends('layouts.app')
@section('title'){{ $news->title }}@endsection
@section('description'){{ $news->meta_description }}@endsection
@section('url'){{url('hir')}}/{{$news->id}}/{{$news->slug}}@endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/hirek" />
@endsection

@section('content')
	<div class="inner_box" style="font-size: 16px;">
		<h2>{{ $news->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$news->user->id)
			<a href="{{url('hir')}}/{{$news->id}}/{{$news->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		{!! $news->body !!}
		@include('partials.tags',['url'=>'hir','obj'=>$news])
		@include('partials.author', ['author'=>'','obj'=>$news])
	</div>
@endsection

