@extends('layouts.app')
@section('description'){{$creation->user->name}} alkotása: {{ $creation->title }}@endsection
@section('url'){{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}" />@endsection
@section('image')@if(!empty($creation->meta_image)){{$creation->meta_image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection

@section('content')
	<div class="inner_box narrow-page">
		<h2>{{ $creation->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$creation->user->id)
			<a href="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			<a href="{{ url('profil',Auth::user()->id) }}/{{Auth::user()->slug}}/alkotasok"><< Alkotásaim</a>
		@endif
		<br>
		{!! nl2br($creation->body) !!}
		@if(!empty($creation->url))
			<div class="inner_box" style="background-color: #fbfbfb">
				<p><a href="{{ $creation->url }}" target="_blank">{{ $creation->meta_title }}</a></p>
				<p><a href="{{ $creation->url }}" target="_blank"><img src="{{$creation->meta_image}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
				<p>{{ $creation->meta_description }}</p>
			</div>
		@endif
		@include('partials.author', ['author'=>'','obj'=>$creation])
    </div>
	@if(Auth::check())
		@include('comments._show', ['comments' => $comments] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
			'commentable_type'	=>'Creation',
			'commentable_url'	=>'alkotas/'.$creation->id.'/'.$creation->slug,
			'commentable_id'	=>$creation->id,
			'name'				=>$creation->user->name,
			'email'				=>$creation->user->email
		] )
	@endif
@endsection