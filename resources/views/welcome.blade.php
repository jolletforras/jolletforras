@extends('layouts.app')

@section('content')
        <h2><img src="images/tarsadalmijollet.jpg" alt="Társadalmi Jóllét" width="100%"></h2>
   		<div class="inner_box">
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
