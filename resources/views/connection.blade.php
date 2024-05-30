@extends('layouts.app')
@section('description', 'Kapcsolat, elérhetőség a Jóllét Forrás oldal működtetőihez. A közösségi fejlesztésről szóló információkat találod itt. Kérdezz tőlünk, csatlakozz hozzánk!')
@section('url', 'https://jolletforras.hu/kapcsolat')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/kapcsolat" />
@endsection

@section('content')
   		<div class="inner_box narrow-page">
            Ha szeretnél kapcsolatba lépni a Jóllét Forrás oldal működtetőivel, szervezőivel, ezeken az elérhetőségeken teheted meg:<br>
            <br>
            Központi mail címünk:<br>
            jolletforras@gmail.com<br>
            <br>
            Az oldalt technikai és tartalmi szempontból is fejleszti, karbantartja és az oldal szellemiségét őrzi a <a href="{{ url('/') }}/csoport/1/jollet-forras-mag" target="_blank">Jóllét Forrás Mag</a> csoport<br>
            <br>
            További fontosságok:<br>
            <a href="https://jolletforras.hu/tudnivalok" target="_blank">Tudnivalók a Jóllét Forrás oldalról</a><br>
            <a href="https://jolletforras.hu/adatkezeles">Adatkezelés</a><br>
            <br>
            Ha hibát találtál az oldalon vagy van javaslatod a továbbfejlesztésre, ezen az űrlapon tudod megírni nekünk: <a href="https://forms.gle/Bn8q7EeSSrtcgMuZ9" target="_blank">A Jóllét Forrás oldal fejlesztési javaslatok</a><br>
        </div>
@endsection
