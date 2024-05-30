@extends('layouts.app')

@section('content')
	@include('partials.tinymce_just_link_js')
	<h2>Csoport felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" style="font-size: 18px">
				A csoport létrehozásakor érthetően és lényegre törően fogalmazd meg a csoport leírását, hogy mi a csoportnak a témája, célja.<br>
				Szintén meg kell fogalmaznod a csoport megállapodást, hogy hogyan vagytok együtt a csoportban és milyen vállalásokat tesznek a csoport tagjai.<br>
				A csoport megállapodás nem lehet ellentétes a Jóllét Forrás oldal <a href="<?php echo e(url('/')); ?>/kozossegimegallapodas " target="_blank">közösségi megállapodásában</a> foglaltakkal, ám mélyítheti, bővítheti azt, valamint konkrét vállalásokat, egyedi jellemzőket tartalmazhat.<br>
				<!-- Fontosság, hogy a csoportod leírása a Jóllét Forrás oldalon regisztráció nélkül is elérhető. Azonban ha valaki még nem regisztrált a Jóllét Forrás oldalon, akkor tud csak csatlakozni a csoporthoz, ha előbb regisztrál, majd pedig a csoportba kéri a felvételét, elfogadva a csoport megállapodást.<br> -->
				Ezért is van jelentősége annak, hogy a csoport leírását jól fogalmazd meg, hogy azt az is érthesse, aki most találkozik a csoporttal először.<br>
				Szerencsés, ha nem egyedül hozod létre a csoportot, így egy kis maggal már közösen tudjátok megfogalmazni mindezeket.<br>
				Ha a csoport a való világban már létezik, akkor annak alapján tudod itt a felületen is létrehozni a csoportot. Ebben az esetben a már meglévő bemutatkozást be tudod ide emelni. Ha esetleg még nincs csoport megállapodásotok, akkor ez jó alkalom, hogy közösen megfogalmazzátok.<br>
				A csoporthoz címkéket tudsz rendelni, így a közösséget kereső tagok, valamint más, kapcsolatot kereső közösségek számára is könnyebben megtalálhatóak lesztek.<br>
				<!-- Aki a csoportot létrehozza a Jóllét Forrás oldalon, az tudja adminisztratív módon kezelni is: módosítani a leírást, törölni a csoportot, valamint szükség esetén kizárni csoporttagot. Arra is érdemes gondolnod, hogy ha csak egyedül leszel ilyen kezelő, az akadályoztatásod a csoport működését okozhatja, ezért érdemes még egy csoporttagot kezelőnek felkérned.<br>-->
				Ha bármiben bizonytalan vagy, kérj segítséget a <a href="{{ url('/') }}/csoport/1/jollet-forras-mag" target="_blank">Jóllét Forrás Magtól</a>!
			</div>
		</div>
	</div>
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('csoport')}}/uj" accept-charset="UTF-8">
				@include('groups._form')
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>
			</form>
		</div>
	</div>
@stop

@section('footer')
	@include('partials.add_tag_script')
	@include('groups._add_local_data_script')
@endsection