@extends('layouts.app')
@section('description', 'Közösségi megállapodás a Jóllét Forrás oldalhoz csatlakozók számára. Úgy vagyunk itt, amilyen társadalmat szeretnénk életre hívni.Téged is hívunk, várunk!')
@section('url', 'https://jolletforras.hu/kozossegimegallapodas')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/kozossegimegallapodas" />
@endsection

@section('content')
   		<div class="inner_box narrow-page">
            <h2>Jóllét Forrás találkozási tér Közösségi megállapodás</h2><br>
            <br>
            Alapértékeink, ami szerint működünk:
            <ul style="padding-left: 25px;">
                <li><b>Alapvető viszonyulásunk</b><br>
                    az elfogadás, bizalom, a békés, szeretetteljes hangnem és a megértésre törekvés.</li>

                <li><b>Személyesség</b><br>
                    A Jóllét Forrás oldalon személyek, saját nevükkel, valós profillal tudnak részt venni. Az új tagok a regisztrációval vállalják, hogy röviden bemutatkoznak és feltöltenek magukról fényképet. Így segítve elő az első kapcsolódásokat.
                    Valamint vállalják, hogy személyes adatlapjukat frissen tartják.</li>

                <li><b>Felelősségvállalás</b><br>
                    A regisztrációval vállaljuk, hogy ezeket a szabályokat betartjuk, a Jóllét Forrás oldalon és a kapcsolódó programokban való cselekvéseinkért felelősséget vállalunk. Ha úgy látjuk, hogy valamely tag szabályt sért, neki jelezzük elsőként, kérve, hogy ne tegye. Ha a szabálysértő nem változtat a hozzáállásán, továbbra is szabályt sért, akkor jelzés alapján az oldal működtetői a tagot kizárhatják az oldal használatából.</li>

                <li><b>Szabad</b><br>
                    Itt minden ember szabad elhatározásából kapcsolódik és vesz részt a közösségben. Senkit nem "használunk", mozgatunk, manipulálunk, hogy valamit akár csak ennek a közösségnek az érdekében vagy bármely
                    tag egyéb más céljai érdekében tegyen.</li>

                <li><b>Tiszta, átlátható működés és kommunikáció</b><br>
                    A Jóllét Forrás oldal áttekinthetően működik (lásd <a href="{{ url('/') }}/tudnivalok " target="_blank">tudnivalók</a> és <a href="{{ url('/') }}/adatkezeles " target="_blank">adatkezelés</a>), folyamatai átláthatóak. </li>

                <li><b>Egyenlőség</b><br>
                    A közösség tagjai egyenlő jogokkal rendelkeznek, amely jogok ugyanakkor nem sérthetik sem a másik szabadságát és egyenlőségét, sem a közösség célját.</li>

                <li><b>Egyén és Közösség</b><br>
                    Az egyéni és a közösségi fontosság összehangolásával működünk, azaz a szolidáris működést szem előtt tartva, ám az egyén szabadságát nem sértve.</li>

                <li><b>Hatalompolitizálás nincs</b><br>
                    Ez a tér nem kapcsolódik semmilyen módon hatalompolitikai formációkhoz, pártokhoz vagy folyamatokhoz. Az oldalon nem lehet semmilyen hatalompolitikai tevékenységet folytatni, sem egyéni, sem csoport szinten.</li>

                <li><b>Fókuszáltság</b><br>
                    Szem előtt tartjuk a küldetést és a célt.  Amikor kezdeményezünk valamit, akkor feltesszük magunknak a kérdést: hogyan szolgálja ez a közös jóllétet? A jelen és korábbi rendszerekkel csak annyiban foglalkozunk, amennyiben információkat és tapasztalatot merítünk belőle, hogy mit szeretnénk másképp és milyen lehetőségek nyílnak ezek elérésére.</li>

                <li><b>Önszerveződés</b><br>
                    Az egyének és csoportok önállóan szerveződhetnek, kapcsolódhatnak. Bárki kezdeményezhet a Jóllét Forrás oldalon új csoportot, beszélgetést, eseményt, készíthet hírt, írást, alkotást, megoszthatja az ügyét, amelyhez társakat keres.</li>

            </ul>
        </div>
@endsection
