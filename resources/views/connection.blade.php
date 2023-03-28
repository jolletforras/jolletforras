@extends('layouts.app')
@section('description', 'Kapcsolat, elérhetőség a Társadalmi Jóllét Portál működtetőihez. A közösségi fejlesztésről szóló információkat találod itt. Kérdezz tőlünk, csatlakozz hozzánk!')
@section('url', 'https://tarsadalmijollet.hu/kapcsolat')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/kapcsolat" />
@endsection

@section('content')
   		<div class="inner_box narrow-page">
            Ha szeretnél kapcsolatba lépni a Társadalmi Jóllét Portál működtetőivel, szervezőivel, ezeken az elérhetőségeken teheted meg:<br>
            <br>
            Központi mail címünk:<br>
            tarsadalmi.jollet@gmail.com<br>
            <br>
            Az oldalt technikai és tartalmi szempontból is fejleszti, karbantartja és az oldal szellemiségét őrzi a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoport<br>
            <br>
            További fontosságok:<br>
            <a href="https://tarsadalmijollet.hu/tudnivalok" target="_blank">Tudnivalók a Portálról</a><br>
            <a href="https://tarsadalmijollet.hu/adatkezeles">Adatkezelés</a><br>
            <br>
            Ha hibát találtál az oldalon vagy van javaslatod a továbbfejlesztésre, ezen az űrlapon tudod megírni nekünk: <a href="https://forms.gle/Bn8q7EeSSrtcgMuZ9" target="_blank">A társadalmi jóllét portál fejlesztési javaslatok</a><br>
        </div>
@endsection
