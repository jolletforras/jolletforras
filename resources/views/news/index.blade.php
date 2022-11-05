@extends('layouts.app')

@section('content')
	<div class="row">
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
		<div class="col-sm-6 text-right">
			<a href="#create_news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('hir')}}/uj" type="submit" class="btn btn-default">Új hír</a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="news_info" style="font-size: 18px">
				Ezen az oldalon találhatod a Portál fejlesztésével kapcsolatos újdonságok bemutatását, valamint a regisztrált tagok  és csoportok híreit.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="create_news_info" style="font-size: 18px">
				Ha hírt szeretnél közzétenni, kérlek, törekedj arra, hogy
				<ul>
					<li>lényegretörően és közérthetően fogalmazz</li>
					<li>a hírben szereplő adatok és tények pontosak legyenek</li>
					<li>a hír kapcsolódjon a társadalmi jólléthez</li>
					<li>a hír címe, tartalma és hangvétele összhangban legyen a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodásban</a> foglaltakkal</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		@for ($i = 0; $i < $num=$newss->count(); $i++)
			<?php $news = $newss[$i]; ?>
			<h3><a href="{{ url('hir',$news->id) }}/{{$news->slug}}">{{ $news->title }}</a></h3>
			@if (Auth::check() && (Auth::user()->id==$news->user->id || Auth::user()->admin))
				<a href="{{url('hir')}}/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
			@endif
			<article>
				<div class="body">{!!$news->body !!}</div>
			</article>
			@if (Auth::check())
				@include('partials.tags',['url'=>'hir','obj'=>$news])
			@endif
			@if($i!=$num-1)<hr>@endif
		@endfor
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'forum'])
@endsection