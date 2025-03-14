@extends('layouts.app')
@section('description', 'Az új világ hangjai a Jóllét Forrás oldalon. Ki milyennek képzeli azt a bizonyos új világot.')
@section('url', 'https://jolletforras.hu/az-uj-vilag-hangjai')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/az-uj-vilag-hangjai" />@endsection
@section('image'){{ url('/images/az-uj-vilag-hangjai.jpeg')}}@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-6">
			<h2>Az új világ hangjai</h2>
		</div>
		<div class="col-sm-6 text-right">
			@if (Auth::check() && Auth::user()->admin)
				<a href="{{url('podcast')}}/uj" type="submit" class="btn btn-default">Új podcast</a>
			@endif
		</div>
	</div>
	<div class="inner_box narrow-page">
		<div class="body">
			<audio controls>
				<source src="{{url('/audio/podcasts')}}/nyito_adas.m4a" type="audio/mpeg">
			</audio>
		</div>
	</div>

	<div class="row inner_box narrow-page" style="text-align: left;">
		<div class="col-sm-12">
				Az új világ hangjai program célja, hogy megmutassuk azoknak az embereknek a jövőképét, akik a közös jóllétért tevékenykednek.<br>
				A programhoz kapcsolódik egy hangcsatorna, amelynek adásaiban egy-egy ilyen beszélgetőtárs mesél a saját tevékenységéről és jövőképéről. Az adásokhoz nyitott tematikus beszélgetések kapcsolódnak, ahol mindenki, aki érintve érzi magát abban a témában, megmutathatja a saját elképzeléseit, tevékenységét és tapasztalatait, így a beszélgetés résztvevői egymáshoz is kapcsolódni tudnak a téma mentén. A tematikus beszélgetéseket azután újabb találkozó vagy épp konkrét közös munka követheti attól függően, hogy a résztvevőknek mire van igényük.<br>
				<br>
				A programhoz többféle módon kapcsolódhatsz:<br>
				<ul>
					<li>A hangcsatorna adásában beszélgetőtársként, a saját jövőképedet és tevékenységedet megmutatva</li>
					<li>A tematikus beszélgetés résztvevőjeként</li>
					<li>Szervező társként</li>
					<li>Támogatóként anyagi vagy egyéb felajánlással</li>
				</ul>
				Ha szeretnél frissiben értesülni az eseményekről és programokról, a levelezőcsoportunkra <a href="https://forms.gle/S18g4L3TAPC9ZMe99">ITT</a> iratkozhatsz fel.<br>
				<br>
				A következő elérhetőségeken tudsz kapcsolatba lépni a program szervezőivel: jolletforras@gmail.com | +36302897830 (P. Horváth Andrea)<br>
				<br>
				Itt találod az egyes adásokat, alattuk a kapcsolódó tematikus beszélgetés eseményoldalával, ahol jelentkezni is tudsz a beszélgetésre.<br>
		</div>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('podcasts._list')
	</div>
@endsection

@section('footer')

@endsection