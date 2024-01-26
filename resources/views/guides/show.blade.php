@extends('layouts.app')
@section('title'){{ $guide->title }}@endsection
@section('description'){{ $guide->meta_description }}@endsection
@section('url'){{url('tudnivalo')}}/{{$guide->id}}/{{$guide->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('tudnivalo')}}/{{$guide->id}}/{{$guide->slug}}" />@endsection
@section('image')@if(!empty($guide->image)){{url('/')}}/images/posts/{{$guide->image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $guide->title }}</h2>
		</div>
        <div class="panel-body">
			{!! $guide->body !!}
			<p><b>{{ $guide->created_at }}</b></p>
			@if (Auth::check() && Auth::user()->admin)
			<a href="{{url('tudnivalo')}}/{{$guide->id}}/{{$guide->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
@endsection

