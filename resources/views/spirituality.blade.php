@extends('layouts.app')
@section('description', 'Ami átfogóan kapcsolódik a Társadalmi Jóllét Portál által is képviselt szellemiséghez, az helyet kap itt.')
@section('url', 'https://tarsadalmijollet.hu/szellemiseg')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/szellemiseg" /> @endsection
@section('image'){{ url('/images/szellemiseg.jpeg')}}@endsection

@section('content')
    <div class="row narrow-page">
        <h2>Szellemiség</h2>
    </div>
    <div class="inner_box narrow-page">
        Ez az oldal azt szolgálja, hogy ami átfogóan kapcsolódik a Társadalmi Jóllét Portál által is képviselt szellemiséghez, az helyet kaphasson itt.<br>
        <br>
        Itt találod <a href="{{ url('/az-uj-vilag-hangjai') }}">Az új világ hangjai</a> programot, az <a href="{{ url('/irasok') }}">Írásokat</a> és az <a href="{{ url('/ajanlo') }}">Ajánlót</a>.<br>
        <br>
        <h3>Milyen is ez a szellemiség?</h3>
        A Portál <a href="{{ url('/') }}" target="_blank">nyitó oldalán</a> írunk erről, valamint ezen értékek és ideák alapján készítettük el a <a href="{{ url('/kozossegimegallapodas') }}" target="_blank">közösségi megállapodást</a>.
    </div>
    <br>
    <div class="row  narrow-page">
        <div class="col-12 col-sm-6 col-md-4 spirituality">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-title">
                    <h3><a href="{{ url('/az-uj-vilag-hangjai') }}">Az új világ hangjai</a></h3>
                </div>
                <div class="image-box">
                    <a href="{{ url('/az-uj-vilag-hangjai') }}">
                        <div class="image" style="background-image:url('images/az-uj-vilag-hangjai.jpeg');"></div>
                    </a>
                </div>
                <div class="card-body">
                    <p>Az új világ hangjai program célja, hogy megmutassuk azoknak az embereknek a jövőképét, akik a társadalmi jóllétért tevékenykednek.</p>
                    <a href="{{ url('/az-uj-vilag-hangjai') }}"> Részletek</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 spirituality">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-title">
                    <h3><a href="{{ url('/irasok') }}">Írások</a></h3>
                </div>
                <div class="image-box">
                    <a href="{{ url('/irasok') }}">
                        <div class="image" style="background-image:url('images/irasok.jpeg');"></div>
                    </a>
                </div>
                <div class="card-body">
                    <p>A Portál tagjainak írásait találod itt. Egy írás a tartalma szerint lehet vélemény, meglátás kifejtése vagy saját kutatás bemutatása. Olvashatod mások írásait, hozzászólhatsz vagy regisztrációt követően elkészítheted és közzéteheted a saját írásodat.</p>
                    <a href="{{ url('/irasok') }}"> Részletek</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 spirituality">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-title">
                    <h3><a href="{{ url('/ajanlo') }}">Ajánló</a></h3>
                </div>
                <div class="image-box">
                    <a href="{{ url('/ajanlo') }}">
                        <div class="image" style="background-image:url('images/ajanlo.jpeg');"></div>
                    </a>
                </div>
                <div class="card-body">
                    <p>Olvasni, nézni, hallgatni valókat találsz itt, egy felvezető ajánlással. Az ajánló röviden tartalmazza az okot, amiért közzétették, valamint tartalmazhat saját meglátást, személyes véleményt is az ajánlott tartalommal kapcsolatban.</p>
                   <a href="{{ url('/ajanlo') }}"> Részletek</a>
                </div>
            </div>
        </div>
    </div>
@endsection
