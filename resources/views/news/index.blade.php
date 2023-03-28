@extends('layouts.app')
@section('description', 'Hírek a Társadalmi Jóllét Portálon. Építő közösségek híreit, információit olvashatod. Csatlakozz hozzánk és adj hírt Te is a saját közösségedről! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/hirek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/hirek" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-3">
			<h2>Hírek</h2><a href="#news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		@if (Auth::check())
		<div class="col-sm-3" style="padding-top:4px;">
			<select id="tag" name="tag" class="form-control">
				@foreach($tags as $key => $val)
					<option value="{{ $key }}">{{ $val }}</option>
				@endforeach
			</select>
		</div>
		@endif
		<div class="col-sm-6 text-right"></div>
	</div>
	<div class="row narrow-page">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="news_info" style="font-size: 18px">
				Ezen az oldalon találhatod a csoportok nyilvános híreit.
			</div>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@for ($i = 0; $i < $num=$newss->count(); $i++)
			<?php $news = $newss[$i]; ?>
			<h3>
				<a href="/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a> -
				<i>@if (Auth::check() || $news->group->public)<a href="{{ url('csoport',$news->group->id) }}/{{$news->group->slug}}">{{ $news->group->name }}</a>@else{{$news->group->name}}@endif</i>
			</h3>
			<br>
			@if ($news->group->isAdmin())
				<a href="{{url('hir')}}/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
			@endif
			<article>
				<div class="body">{!!$news->body !!}</div>
				@include('partials.author', ['author'=>'','obj'=>$news])
			</article>
			@if (Auth::check())
				@include('partials.tags',['url'=>'hir','obj'=>$news])
			@endif
			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'hir'])
@endsection