@extends('layouts.app')
@section('description', 'A Társadalmi Jóllét Portál találkozási tér mindazok számára, akik részt vesznek a társadalmi jóllétet megvalósító, emberközpontú új világ megteremtésében.')
@section('url', 'https://tarsadalmijollet.hu/')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/" />
@endsection

@section('content')
        <div id="banner">
            <h2><img src="images/tarsadalmijollet.png" alt="Társadalmi Jóllét"></h2>
            <div class="container">
                <p>
                    A nagy fordulat egy ember életében: <br>
                    "nekem mi jut?" helyett: "mi fakad belőlem?"<br>
                    S ez elég ahhoz, hogy a bentről-fakadó<br>
                    fényes legyen és folyton tisztuló.<br>
                    <span>(Weöres Sándor)</span>
                </p>
            </div>
        </div>
   		<div class="inner_box narrow-page">
            A Társadalmi Jóllét Portál találkozási tér kíván lenni mindazon emberek és csoportok számára, akik cselekvőként részt vesznek a társadalmi jóllétet megvalósító, emberközpontú új világ megteremtésében.
            Célunk, hogy ez az oldal a lehető legjobban támogassa a cselekvők egymásra találását és mind erőteljesebb összekapcsolódását.<br>
            <b>Téged várunk, ha:</b>
            <ul>
                <li>felismerted, hogy a társadalmi jóllétet nem mások fogják majd “felülről” megteremteni számunkra, hanem mi magunk.</li>
                <li>ebben a munkában Te is szeretnél részt venni vagy már részt is veszel.</li>
            </ul>
            Akár még csak keresed a társaidat, akár már van közösséged, amely önmagában erősödik, akár pedig ráébredtél, hogy fontos más emberekhez és közösségekhez is kapcsolódni, “hálózatot szőni”, itt a helyed!
        </div>

		@include('about_us')
@endsection
