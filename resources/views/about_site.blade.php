@extends('layouts.app')
@section('description', 'Tudnivalók a Társadalmi Jóllét Portál működésével kapcsolatban, az átláthatóság és fenntarthatóság jegyében. Olvasd el és ha tetszik, csatlakozz hozzánk!')
@section('url', 'https://tarsadalmijollet.hu/tudnivalok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tudnivalok" />
@endsection

@section('content')
	<div class="inner_box narrow-page">
		<h2>Tudnivalók Társadalmi Jóllét Portálról</h2><br>
		<br>
		<b>Kik állnak a portál működése mögött?</b><br>
		A tarsadalmijollet.hu tárhely szolgáltatását (virtuális szerverét) támogatásként jelenleg a <a href="https://products.symbion.hu/hu" target="_blank">Symbion Products Kft.</a> nyújtja.
		A domain használója <a href="https://tarsadalmijollet.hu/profil/72/ludescher-otto" target="_blank">Ludescher Ottó</a>, a Portál kezdeményezője és fejlesztője. Jelenleg ő viseli a domain fenntartás költségét. Ennek oka, hogy a Társadalmi Jóllét Portálnak magának nincs formális szerveződése. A jelenlegi domain használó kifejezi szándékát, hogy amint ez változik, a jogot haladéktalanul és díjmentesen átengedi annak a szerveződésnek.
		A Portál működtetését a <a href="https://tarsadalmijollet.hu/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoport vállalja. Ez jelenti a fejlesztést, tájékoztatást, a regisztráltak támogatását és a Portál kommunikációját egyaránt.<br>
		<br>
		<b>Ki és hogyan veheti igénybe az oldal szolgáltatásait?</b><br>
		A portál szolgáltatásai alatt mindazokat a funkciókat értjük, amelyeket az oldalon használni lehet.
		A szolgáltatásokat bárki igénybe veheti, aki a Társadalmi Jóllét Portál küldetésével, céljával és értékrendjével egyetért, a <a href="https://tarsadalmijollet.hu/kozossegimegallapodas" target="_blank">Közösségi megállapodást</a> elfogadja és regisztrál az oldalon. Teheti ezt mindaddig, amíg regisztrációja élő.
		Jelenleg az oldal szolgáltatásait a támogatóknak és az önkéntes munkának köszönhetően díjmentesen lehet igénybe venni.
		A későbbiekben szeretnénk kialakítani azt a felajánlási rendszert, amelyben támogatni lehet az oldal működését és fejlesztését az önkéntességen túl kapcsolatokkal, eszközökkel, pénzadományokkal is.<br>
		<br>
		<b>Hogyan és mikor lehet valakit kizárni a Társadalmi Jóllét Portálról?</b><br>
		Működésünket a Közösségi megállapodás határozza meg. Az abban foglaltak szerint ha valaki a Közösségi megállapodást megsérti, akkor a Portálról kizárható. A Közösségi megállapodás megsértésének tényét bármely tag jelezheti, közvetlenül az érintett személynek. Amennyiben az érintett személy nem fejezi be a megállapodás megsértését vagy azt ismételten megsérti, akkor ezt a Társadalmi Jóllét Mag csoportnak is jelezni kell.
		Ha az érintett tag a Mag csoporttól érkező visszajelzés hatására sem változtat vagy ismétli a megállapodás megsértését, akkor a tag tájékoztatása mellett a Mag csoport az érintett tagot a Portálról kizárja.
		A kizárás azt jelenti, hogy regisztrációja inaktív állapotba kerül, a Portál szolgáltatásait nem tudja igénybe venni.
		Amennyiben igazolhatóan ugyanaz a személy új mail címmel új regisztrációval szeretne élni, akkor ezt az új regisztrációt is visszautasítja a Mag csoport.<br>
		<br>
		<b>Hogyan történik a portál fejlesztése?</b><br>
		A Portált alapjaitól kezdve <a href="https://tarsadalmijollet.hu/profil/72/ludescher-otto" target="_blank">Ludescher Ottó</a> fejleszti. A fejlesztésébe szabadon be lehet kapcsolódni. Aki a Portálra regisztrál és szeretné felajánlani fejlesztői tudását a Portál fejlesztésébe, annak Ottó hozzáférést biztosít a kódhoz.
		Ha tehát szakmai tudásoddal szeretnél bekapcsolódni a fejlesztésbe, kérjük, vedd fel a kapcsolatot Ottóval.<br>
		A Portált a regisztrált tagoktól érkező visszajelzések alapján fejlesztjük tovább.
		A fejlesztési javaslatokat az alábbi űrlappal gyűjtjük: <a href="https://forms.gle/Bn8q7EeSSrtcgMuZ9" target="_blank">A társadalmi jóllét portál fejlesztési javaslatok</a><br>
		Mivel jelenleg a fejlesztést teljesen önkéntesen végezzük, ezért a beérkezett javaslatokat a Társadalmi Jóllét Mag csoport nézi át az alábbi szempontok szerint és dönt a fejlesztésről:
		<ol>
			<li>Összhangban van-e a fejlesztési javaslat a Portál szabályzatával és küldetésével? Ha igen, akkor zöld jelzést kap ebből a szempontból. Ha nem, akkor piros jelzést kap. Ezeket a javaslatokat elutasítással zárjuk le.</li>
			<li>Mennyire elengedhetetlen a fejlesztés azért, hogy a Portál a vállalt feladatát teljesíthesse? Ennek alapján egy fontossági pontszámot kap a javaslat, 1-10 között. 1= nagyon fontos</li>
			<li>Mekkora időráfordítás becsülhető a fejlesztés megvalósításához? Ennek alapján egy időtényező pontszámot kap a javaslat, 1-10 között. 1= rövid időráfordítás</li>
			<li>Van-e a javaslathoz kapcsolódóan másik javaslat vagy már megvalósítás, amellyel együttesen nem valósítható meg mindkét fejlesztés, egymást kizárják a javaslatok?
			Ha nincs ütközés, akkor zöld jelzést kap ebből a szempontból és önállóan, az 1. és 2. pont szerint mérlegelve döntünk a fejlesztésről.
			Ha van, akkor mindkét javaslatot mérlegeljük az 1. és 2. pont alapján is, és eszerint döntünk  a fejlesztésről. Amelyiket elvetjük, azt a javaslatot indoklással elutasítással zárjuk le.</li>
		</ol>
		Egyetlen olyan fejlesztési javaslatot sem vetünk el, amely a szabályzattal és küldetéssel összhangban van és nem ütközik másik javaslattal. Ezek a javaslatok a pontszámok alapján nyitva maradnak és a pontszámok alapján sorrendet állítunk közöttük.
		Azaz erőforrás esetén azokat is később megvalósíthatjuk.<br>
		<br>
		<b>Átláthatóság biztosítása a fejlesztésekkel kapcsolatban</b><br>
		<ul>
			<li>A fejlesztési javaslatokat gyűjtő táblázatot minden regisztrált tag ezen a linken megtekintheti, így saját és mások javaslatait és a javaslatok állapotát is nyomon lehet követni.</li>
			<li>Aki javaslatot küldött be, az értékelés eredményéről közvetlenül is tájékoztatjuk.</li>
			<li>A megvalósult fejlesztésekről folyamatosan tájékoztatjuk a regisztrált tagokat a Hírek oldalon.</li>
		</ul>
 	</div>
@endsection


