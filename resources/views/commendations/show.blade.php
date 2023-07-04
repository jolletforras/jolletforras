@extends('layouts.app')

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $commendation->title }}</h2>
			@if (Auth::check() && (Auth::user()->id==$commendation->user->id || Auth::user()->admin))
				<a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
		</div>
        <div class="panel-body">
			{!! nl2br($commendation->body) !!}
			@if(!empty($commendation->url))
				<br>
				<a href="{!! $commendation->url !!}" target="_blank">{!! substr($commendation->url,0,50) !!}</a><br>
			@endif
			@include('partials.author', ['author'=>'','obj'=>$commendation])
	    </div>
    </div>
	@include('comments._show', [
	'comments' => $comments,
    'commentable_type'	=>'Commendation',
    'commentable_url'	=>'commendation/'.$commendation->id.'/'.$commendation->slug,
    'commentable'	=>$commendation
	] )
@endsection

