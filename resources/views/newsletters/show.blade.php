@extends('layouts.app')
@section('title'){{ $newsletter->title }}@endsection
@section('description'){{ $newsletter->meta_description }}@endsection
@section('url'){{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('iras')}}/{{$newsletter->id}}/{{$newsletter->slug}}" />@endsection
@section('image')@if(!empty($newsletter->image)){{url('/')}}/images/posts/{{$newsletter->image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $newsletter->title }}</h2>
		</div>
        <div class="panel-body">
			{!! $newsletter->body !!}
			<p><b>{{ $newsletter->created_at }}</b></p>
			@if (Auth::check() && Auth::user()->admin)
			<a href="{{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
	    </div>
    </div>
@endsection

