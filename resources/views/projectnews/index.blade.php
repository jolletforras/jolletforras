@extends('layouts.app')
@section('title'){{ $project->title }} kezdeményezés hírei @endsection
@section('description'){{ $project->meta_description }} @endsection
@section('url'){{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug.'/hirek'}}@endsection
@section('canonical')<link rel="canonical" href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/hirek"  />@endsection
@section('image'){{ url('/images/projects') }}/{{ $project->id.'.jpg'}}@endsection

@section('content')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@if($project->isAdmin())
		<div class="row">
			<div class="col-sm-9" style="padding-top:4px;"></div>
			<div class="col-sm-3 text-right">
				<a href="#create_news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}/hir/uj" type="submit" class="btn btn-default">Új hír</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="inner_box collapse" id="create_news_info" style="font-size: 18px">
					A kezdeményezés kezelőjeként lehetőséged van a kezdeményezés aktuális történéseit, eseményeit egy-egy hírbejegyzésben összefoglalni. A láthatóságnál beállíthatod, hogy azok is értesülhessenek a közösséged legújabb híreiről, akik nem tagjai a portálnak.
				</div>
			</div>
		</div>
		<hr style="margin-top:2px;">
		@else
			<br>
		@endif
		@for ($i = 0; $i < $num=$newss->count(); $i++)
			<?php $news = $newss[$i]; ?>
			<h3><a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a></h3>
			@if ($news->project->isAdmin())
				<a href="{{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
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