@extends('layouts.app')
@section('description'){{$user->name}} írásai a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Olvasd el a Portál tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!@endsection
@section('url')https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/irasok @endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/irasok" />
@endsection

@section('content')
	@include('profiles.partials.profile_menu')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@if (Auth::check() && Auth::user()->id==$user->id)
		<div class="row">
			<div class="col-sm-9"></div>
			<div class="col-sm-3 text-right">
				<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új Írás</a>
			</div>
		</div>
		@include('articles._new_article_info')
		@endif
		@for ($i = 0; $i < $num=$articles->count(); $i++)
			<?php $article = $articles[$i]; ?>
			<h3><a href="/iras/{{$article->id}}/{{$article->slug}}">{{ $article->title }}</a></h3>
			<article>
				<div class="body">{!!$article->body !!}</div>
			</article>
			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection