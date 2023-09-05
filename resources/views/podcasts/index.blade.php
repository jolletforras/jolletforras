@extends('layouts.app')
@section('description', 'Az új világhangjai a Társadalmi Jóllét Portálon. Ki milyennek képzeli azt a bizonyos új világot.')
@section('url', 'https://tarsadalmijollet.hu/az-uj-vilag-hangjai')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/az-uj-vilag-hangjai" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-6">
			<h2>Az új világ hangjai</h2><a href="#podcast_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		<div class="col-sm-6 text-right">
			@if (Auth::check() && Auth::user()->admin)
				<a href="{{url('podcast')}}/uj" type="submit" class="btn btn-default">Új podcast</a>
			@endif
		</div>
	</div>
	<div class="row narrow-page">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="podcast_info" style="font-size: 18px">
				Ezen az oldalon találhatod "Az új világ hangjai" podcast sorozat beszélgetéseit.<br>
				Sokféle jövőt elképzelhetünk. Az új világ hangjai hangcsatorna azokat az elképzeléseket gyűjti egybe, amelyekben a világunk egy olyan hely, ahol az emberek biztonságban érzik magukat, felszabadultan önmaguk lehetnek, miközben megvan mindenük ahhoz, hogy életük kiteljesedhessen. Ehhez a kiteljesedéshez arra van szükségük, hogy értéket adjanak a helyi közösségüknek, ami ezáltal megteremti tagjai számára az egészséges, boldog, kiegyensúlyozott élethez szükséges támogatói környezetet. Vagyis társadalmi jóllétet valósítunk meg, együtt. A beszélgetések egy-egy témakört fognak meg ebben a jövőképben.
			</div>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('podcasts._list')
	</div>
@endsection

@section('footer')

@endsection