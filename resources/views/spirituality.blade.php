@extends('layouts.app')
@section('description', 'Ez az oldal azt szolgálja, hogy ami átfogóan kapcsolódik a Társadalmi Jóllét Portál által is képviselt szellemiséghez, az helyet kaphasson itt.')
@section('url', 'https://tarsadalmijollet.hu/szellemiseg')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/szellemiseg" /> @endsection
@section('image'){{ url('/images/szellemiseg.jpg')}}@endsection

@section('content')
    <div class="row narrow-page">
        <h2>Szellemiség</h2>
    </div>
    <div class="inner_box narrow-page">
        Ez az oldal azt szolgálja, hogy ami átfogóan kapcsolódik a Társadalmi Jóllét Portál által is képviselt szellemiséghez, az helyet kaphasson itt.<br>
        <br>
        Itt találod <a href="{{ url('/az-uj-vilag-hangjai') }}" target="_blank">Az új világ hangjai</a> programot, az <a href="{{ url('/irasok') }}" target="_blank">Írásokat</a> és az <a href="{{ url('/ajanlo') }}" target="_blank">Ajánlót</a>.<br>
        <br>
        <h3>Milyen is ez a szellemiség?</h3>
        A Portál <a href="{{ url('/') }}" target="_blank">nyitó oldalán</a> írunk erről, valamint ezen értékek és ideák alapján készítettük el a <a href="{{ url('/kozossegimegallapodas') }}" target="_blank">közösségi megállapodást</a>.
    </div>
@endsection
