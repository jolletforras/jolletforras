@extends('layouts.app')
@section('description', 'Események a Társadalmi Jóllét Portálon. Programok, fórumok, lehetőségek, amelyek a társadalmi jóllét megvalósítását segítik. Nézz szét, regisztrálj, kapcsolódj!')
@section('url', 'https://tarsadalmijollet.hu/esemenyek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/esemenyek" />
@endsection

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $event->title }}</h2>
		</div>
		<div class="panel-body">
			{!! $event->body !!}
			@if ($has_access)
				<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			<br>
			@include('partials.author', ['author'=>'Eseményt felvette: ','obj'=>$event])
		</div>
	</div>
	@if(Auth::check())
		@include('comments._show', [
		'comments' => $comments,
		'commentable_type'	=>'Event',
		'commentable_url'	=>'esemeny/'.$event->id.'/'.$event->slug,
		'commentable'	=>$event
		] )
	@endif
@endsection