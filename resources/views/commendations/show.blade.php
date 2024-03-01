@extends('layouts.app')
@section('title'){{ $commendation->title }}@endsection
@section('description'){{$commendation->user->name}} ajánlója - {{ $commendation->meta_title }}@endsection
@section('url'){{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}" />@endsection
@section('image')@if(!empty($commendation->meta_image)){{$commendation->meta_image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection

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
				<p><a href="{{ $commendation->url }}" target="_blank">@if(empty($commendation->meta_title)){{ $commendation->url}}@else{{ $commendation->meta_title }}@endif</a></p>
				@if(!empty($commendation->meta_image))
				<p><a href="{{ $commendation->url }}" target="_blank"><img src="{{$commendation->meta_image}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
				@endif
				@if(!empty($commendation->meta_description))
				<p>{{ $commendation->meta_description }}</p>
				@endif
			</div>
		@endif
		@include('partials.author', ['author'=>'','obj'=>$commendation])
		@if(Auth::check())
			@include('commendations._tags')
		@endif
    </div>

	@if(Auth::check() && Auth::user()->isGroupAdmin())
	<p class="narrow-page"><button class="btn btn-default" type="button" onclick="get_group_admin_block()" id="btn_group_admin_block"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Csoporthoz való hozzáadás/törlés</button></p>
	<div class="inner_box narrow-page" id="group_admin_block" style="display: none;">... hamarosan betölt</div>
	@endif

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

		@include('partials.post_group_script', [
			'post_type'	=>'commendation',
			'post_id'	=>$commendation->id,
		] )
	@endif
@endsection
