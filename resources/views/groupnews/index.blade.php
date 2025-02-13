@extends('layouts.app')
@section('title'){{ $group->name }} csoport hírei @endsection
@section('description'){{ $group->meta_description }} @endsection
@section('url'){{url('csoport')}}/{{$group->id}}/{{$group->slug.'/hirek'}}@endsection
@section('canonical')<link rel="canonical" href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/hirek"  />@endsection
@section('image'){{ url('/images/groups') }}/{{ $group->id.'.jpg'}}@endsection

@section('content')
	@include('groups._group_menu')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@if($group->isAdmin())
		<div class="row">
			<div class="col-sm-9" style="padding-top:4px;"></div>
			<div class="col-sm-3 text-right">
				<a href="#create_news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{ url('csoport',$group->id) }}/{{$group->slug}}/hir/uj" type="submit" class="btn btn-default">Új hír</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="inner_box collapse" id="create_news_info" style="font-size: 18px">
					Csoportkezelőként lehetőséged van a csoport aktuális történéseit, eseményeit egy-egy hírbejegyzésben összefoglalni. A láthatóságnál beállíthatod, hogy azok is értesülhessenek a közösséged legújabb híreiről, akik nem tagjai a csoportnak.
				</div>
			</div>
		</div>
		<hr style="margin-top:2px;">
		@else
			<br>
		@endif
		@for ($i = 0; $i < $num=$newss->count(); $i++)
			<?php $news = $newss[$i]; ?>
			<h3><a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a></h3>
			@if ($news->group->isAdmin())
				<a href="{{url('csoport')}}/hir/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
			@endif
			<article>
				<div class="body">{!!$news->body !!}</div>
			</article>
			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection

@section('footer')
@endsection