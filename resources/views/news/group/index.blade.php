@extends('layouts.app')
@section('description', 'Csoportok hírei a Társadalmi Jóllét Portálon. Építő közösségek híreit, információit olvashatod. Csatlakozz hozzánk és adj hírt Te is a saját közösségedről! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/csoport/hirek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/csoport/hirek" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-4">
			<h2>Csoport hírek</h2><a href="#news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		@if (Auth::check())
			<div class="col-sm-8" style="padding-top:4px; width: 250px;">
			<select id="tag" name="tag" class="form-control">
				@foreach($tags as $key => $val)
					<option value="{{ $key }}">{{ $val }}</option>
				@endforeach
			</select>
		</div>
		@endif
	</div>
	<div class="row narrow-page">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="news_info" style="font-size: 18px">
				Ezen az oldalon találhatod a csoportok híreit.
			</div>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('news.group._list')
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'csoport/hir'])
@endsection