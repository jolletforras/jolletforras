@extends('layouts.app')
@section('description', 'Az új világ hangjai a Társadalmi Jóllét Portálon. Ki milyennek képzeli azt a bizonyos új világot.')
@section('url', 'https://tarsadalmijollet.hu/az-uj-vilag-hangjai')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/az-uj-vilag-hangjai" />@endsection
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
			<iframe class="podcast-iframe" src="https://podcasters.spotify.com/pod/show/az-uj-vilag-hangjai/embed/episodes/Nyit-ads-e28r9q3" style="height:100%;width:100%;" frameborder="0" scrolling="no"></iframe>
		</div>
	</div>

	<div class="row inner_box narrow-page" style="text-align: left;">
		<div class="col-sm-12">
				Az új világ hangjai program célja, hogy megmutassuk azoknak az embereknek a jövőképét, akik a társadalmi jóllétért tevékenykednek.<br>
				A programhoz kapcsolódik egy hangcsatorna, amelynek adásaiban egy-egy ilyen beszélgetőtárs mesél a saját tevékenységéről és jövőképéről. Az adásokhoz nyitott tematikus beszélgetések kapcsolódnak, ahol mindenki, aki érintve érzi magát abban a témában, megmutathatja a saját elképzeléseit, tevékenységét és tapasztalatait, így a beszélgetés résztvevői egymáshoz is kapcsolódni tudnak a téma mentén. A tematikus beszélgetéseket azután újabb találkozó vagy épp konkrét közös munka követheti attól függően, hogy a résztvevőknek mire van igényük.<br>
				<br>
				A programhoz többféle módon kapcsolódhatsz:<br>
				<ul>
					<li>A hangcsatorna adásában beszélgetőtársként, a saját jövőképedet és tevékenységedet megmutatva</li>
					<li>A tematikus beszélgetés résztvevőjeként</li>
					<li>Szervező társként</li>
					<li>Támogatóként anyagi vagy egyéb felajánlással</li>
				</ul>
				Ha szeretnél frissiben értesülni az eseményekről és programokról, a levelezőcsoportunkra itt iratkozhatsz fel: <span style="display: inline-block;">az-uj-vilag-hangjai@googlegroups.com</span><br>
				<br>
				A következő elérhetőségeken tudsz kapcsolatba lépni a program szervezőivel: tarsadalmi.jollet@gmail.com | +36302897830 (Pataki Andrea)<br>
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