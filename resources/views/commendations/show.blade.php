@extends('layouts.app')

@section('content')
	<div class="inner_box narrow-page">
		<h2>{{ $commendation->title }}</h2>
		@if (Auth::check() && (Auth::user()->id==$commendation->user->id || Auth::user()->admin))
			<a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
		@endif
		<br>
		{!! nl2br($commendation->body) !!}
		@if(!empty($commendation->url))
			<div class="inner_box" style="background-color: #fbfbfb">
				<p><a href="{{ $commendation->url }}" target="_blank">{{ $commendation->meta_title }}</a></p>
				<p><a href="{{ $commendation->url }}" target="_blank"><img src="{{$commendation->meta_image}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
				<p>@if(strlen($commendation->meta_description)>300){{ mb_substr($commendation->meta_description,0,300) }} ... @else {{ $commendation->meta_description }} @endif</p>
			</div>
		@endif
		@include('partials.author', ['author'=>'','obj'=>$commendation])
    </div>
	@if(Auth::check())
		@include('comments._show', ['comments' => $comments] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
			'commentable_type'	=>'Commendation',
			'commentable_url'	=>'ajanlo/'.$commendation->id.'/'.$commendation->slug,
			'commentable_id'	=>$commendation->id,
			'name'				=>$commendation->user->name,
			'email'				=>$commendation->user->email
		] )
	@endif
@endsection
