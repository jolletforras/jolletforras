@extends('layouts.app')
@section('title'){{ $article->title }}@endsection
@section('description'){{ $article->meta_description }}@endsection
@section('url'){{url('iras')}}/{{$article->id}}/{{$article->slug}}@endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/irasok" />
@endsection

@section('content')
	<div class="inner_box" style="font-size: 16px;">
		<h2>{{ $article->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$article->user->id)
			<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		{!! $article->body !!}
		@include('partials.author', ['author'=>'','obj'=>$article])
    </div>
@endsection

