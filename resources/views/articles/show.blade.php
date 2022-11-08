@extends('layouts.app')
@section('description', 'Írások a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Olvasd el a Portál tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/irasok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/irasok" />
@endsection

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $article->title }}</h2>
		</div>
        <div class="panel-body">
			@if (Auth::check() && Auth::user()->id==$article->user->id)
				<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			{!! $article->body !!}
			@include('partials.author', ['author'=>'','obj'=>$article])
	    </div>
    </div>
@endsection

