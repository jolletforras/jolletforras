@extends('layouts.app')
@section('description', 'Csoportok a Társadalmi Jóllét Portálon. Nyilvánosan elérhető bemutatkozások. Csatlakozz csoporthoz vagy hozd létre itt közösséged online csoportját! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/csoportok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/csoportok" />
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-3">
			<h2>Csoportok</h2><a href="#group_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		@if(Auth::check())
			<div class="col-sm-3" style="padding-top:4px;">
				<select id="tag" name="tag" class="form-control">
					@foreach($tags as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-sm-1" style="padding-top:4px;">
			</div>
			<div class="col-sm-3" style="padding-top:4px;">
				<select id="city" onchange="CityFilter();" name="city">
					<option value="" selected="selected">Minden település</option>
					@foreach(constx('CITY') as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-sm-2 text-right">
				<a href="#create_group_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('csoport')}}/uj" type="submit" class="btn btn-default">Új csoport</a>
			</div>
		@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="group_info" style="font-size: 18px">
				Csatlakozhatsz már meglévő csoporthoz és hozhatsz létre új csoportot is. Ha már tagja vagy egy csoportnak, akkor másokat is meghívhatsz.<br>
				<b>Fontosság, hogy ez a felület nem helyettesíti a valós csoport életet, mindössze online támogatást ad a valós csoport élet szervezéséhez, a kapcsolattartáshoz.</b><br>
				<br>
				<b>Mire figyelj, amikor egy csoporthoz csatlakozol?</b><br>
				Alaposan olvasd el a <u>csoport leírását</u> és a <u>csoport megállapodást</u>. Akkor csatlakozz, ha valóban hívást érzel, hogy aktívan részt vegyél a csoport munkájában és vállalni tudod a csoport megállapodásban leírtakat.<br>
				A csoportba úgy tudsz belépni, ha elfogadod a csoport megállapodást. Ez egyben azt a felelősséget is jelenti, hogy betartod azt, és ha nem tartod be, akkor a csoport kezelője kizárhat a csoportból. Ezért ha bármiben bizonytalan vagy, akkor beszélj a csoport kezelőjével, illetve a tagokkal. Ha pedig szándékod ellenére sértetted meg a csoport megállapodást, akkor kérj tisztázó beszélgetést.<br>
				<br>
				<b>Hogyan működik egy csoportban a kommunikáció ezen a felületen?</b><br>
				A csoportban létrehozhatsz új témát, ezzel új beszélgetést nyitsz meg. Valamint hozzászólhatsz más által nyitott témához.<br>
				Alapbeállításban minden új témáról értesítést kapsz emailben. Ha érdekel a téma, két lehetőséged van:<br>
				<ul>
					<li>Feliratkozol a témára. Ebben az esetben minden hozzászólásról email értesítést kapsz mindaddig, amíg fel vagy iratkozva.</li>
					<li>Hozzászólsz. Ebben az esetben is értesítést kapsz az újabb hozzászólásokról.</li>
				</ul>
				Minden értesítő emailben lehetőséged van leállítani a további értesítéseket.<br>
				További fontosság, hogy a tiszta, átlátható kommunikáció elvét követve a hozzászólásodat nem tudod módosítani vagy törölni. Ha bármit pontosítani vagy javítani szeretnél, akkor azt új hozzászólásban teheted meg.<br>
				Fontos, hogy kommunikációd legyen erőszakmentes, értően és alaposan olvasd el a témát, a hozzászólásokat, kérdezz vissza, ha nem érthető bármi, és törekedj a lényegre törő fogalmazásra. Törekedj arra is, hogy ha a témát összetettnek látod, akkor vigyétek ki ezt az írásos online térből és személyesen beszéljétek meg.<br>
				<br>
				<!-- <b>Mire figyelj, ha szeretnél meghívni valakit a csoportba?</b><br>
                A meghíváshoz készítettünk egy meghívó email szöveget. Ebben szerepel az, hogy Te hívod meg, valamint a csoport leírása is. Valamint arra is kitér a meghívó, ha a meghívott még nem regisztrált a portálon, azt hogyan teheti meg.<br>
                Ami azonban a Te egyéni feladatod, hogy előzetesen megbeszéljétek, miért ajánlod neki a csoportot és hogy szeretne-e a ennek alapján csatlakozni.<br>--
                <br>-->
				<b>Mi a teendő, ha már nem szeretnél a csoport tagja lenni?</b><br>
				Minden csoportra igaz kell legyen, hogy szabadon csatlakozhatsz hozzá -ha elfogadod a csoport megállapodást- és szabadon el is hagyhatod.<br>
				A csoport főoldalán találsz egy “Kilépek a csoportból” gombot. Erre kattintva tudod elhagyni a csoportot. A portál közösségi megállapodását szem előtt tartva kérjük, ne felejtsd el a csoport tagjait kilépés előtt tájékoztatni, hogy miért hagyod el a csoportot. Ha konfliktus helyzet miatt jutottál erre a döntésre, javasoljuk, hogy kilépés előtt kérj tisztázó beszélgetést, szükség szerint mediálást.<br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="create_group_info" style="font-size: 18px">
				<b>Mire figyelj, amikor egy új csoportot hozol létre?</b><br>
				A csoport létrehozásakor érthetően és lényegre törően fogalmazd meg a csoport leírását, hogy mi a csoportnak a témája, célja.<br>
				Szintén meg kell fogalmaznod a csoport megállapodást, hogy hogyan vagytok együtt a csoportban és milyen vállalásokat tesznek a csoport tagjai.<br>
				A csoport megállapodás nem lehet ellentétes a portál <a href="<?php echo e(url('/')); ?>/kozossegimegallapodas " target="_blank">közösségi megállapodásában</a> foglaltakkal, ám mélyítheti, bővítheti azt, valamint konkrét vállalásokat, egyedi jellemzőket tartalmazhat.<br>
				<!-- Fontosság, hogy a csoportod leírása a portálon regisztráció nélkül is elérhető. Azonban ha valaki még nem regisztrált a portálon, akkor tud csak csatlakozni a csoporthoz, ha előbb regisztrál, majd pedig a csoportba kéri a felvételét, elfogadva a csoport megállapodást.<br> -->
				Ezért is van jelentősége annak, hogy a csoport leírását jól fogalmazd meg, hogy azt az is érthesse, aki most találkozik a csoporttal először.<br>
				Szerencsés, ha nem egyedül hozod létre a csoportot, így egy kis maggal már közösen tudjátok megfogalmazni mindezeket.<br>
				Ha a csoport a való világban már létezik, akkor annak alapján tudod itt a felületen is létrehozni a csoportot. Ebben az esetben a már meglévő bemutatkozást be tudod ide emelni. Ha esetleg még nincs csoport megállapodásotok, akkor ez jó alkalom, hogy közösen megfogalmazzátok.<br>
				A csoporthoz címkéket tudsz rendelni, így a közösséget kereső tagok, valamint más, kapcsolatot kereső közösségek számára is könnyebben megtalálhatóak lesztek.<br>
				<!-- Aki a csoportot létrehozza a portálon, az tudja adminisztratív módon kezelni is: módosítani a leírást, törölni a csoportot, valamint szükség esetén kizárni csoporttagot. Arra is érdemes gondolnod, hogy ha csak egyedül leszel ilyen kezelő, az akadályoztatásod a csoport működését okozhatja, ezért érdemes még egy csoporttagot kezelőnek felkérned.<br>-->
				Ha bármiben bizonytalan vagy, kérj segítséget a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Magtól</a>!
			</div>
		</div>
	</div>
	<div class="row" id="result">
		<hr style="margin-top:0px;">
		@include('groups._group_list')
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'csoport'])
	<script>
		function CityFilter() {
			select_city=document.getElementById("city");
			var x = select_city.selectedIndex;
			var y = select_city.options;
			var city = y[x].value;

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: '{{ url('group/filter') }}',
				data: {
					_token: CSRF_TOKEN,
					city: city,
				},
				success: function(data) {
					$('#result').html(data.html);
				}
			});
		}

	</script>
@endsection