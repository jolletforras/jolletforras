@extends('layouts.app')
@section('title'){{ $category->title }}@endsection
@section('description'){{ $category->meta_description }}@endsection
@section('url'){{url('alkotasok')}}/{{$category->id}}/{{$category->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('alkotasok')}}/{{$category->id}}/{{$category->slug}}"  />@endsection
@section('image'){{ url('/images/categories') }}/{{ $category->id.'.jpg?'.$category->photo_counter}}@endsection

@section('content')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<h2>{{ $category->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$category->user->id)
			<a href="{{url('alkotas-temakor')}}/{{$category->id}}/{{$category->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a>
			<a href="{{url('temakor')}}/{{$category->id}}/{{$category->slug}}/kepfeltoltes" type="submit" class="btn btn-default">Képfeltöltés</a>
		@endif
		@if(file_exists(public_path('images/categories/'.$category->id.'.jpg')))
			<p style="text-align: center;"><img src="{{ url('/images/categories') }}/{{ $category->id}}.jpg?{{$category->photo_counter}}" style="max-width: 50%;"></p>
		@endif
		{!! $category->body !!}
		<hr>
		<div class="row">
            @include('creations._list')
		</div>
	</div>
@endsection
