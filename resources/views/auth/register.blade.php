@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="inner_box" style="font-size: 18px">
                Kedves Érdeklődő!<br>
                <br>
                Jó, hogy idetaláltál!<br>
                <br>
                Néhány fontosság, mielőtt elkezded a regisztrációt:<br>
                <ul>
                    <li>A <a href="{{ url('/') }} " target="_blank">nyitóoldalon</a> részleteztük, mi mindent tehetsz itt a regisztrációt követően.</li>
                    <li>Alapelv, hogy arccal és névvel legyünk itt. Kérjük, hogy regisztrációkor valós teljes nevedet add meg és tölts fel fényképet is.</li>
                    <li>Figyelj arra, hogy pontosan add meg a mail címedet, mert erre küldünk majd egy megerősítő e-mailt, csak ez után válik élessé a regisztrációd.</li>
                    <li>A regisztrációhoz szükséges elolvasnod és elfogadnod a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a> és az <a href="{{ url('/') }}/adatkezeles " target="_blank">Adatkezelést</a>.
                        Ajánljuk, hogy szintén olvasd el a <a href="{{ url('/') }}/tudnivalok " target="_blank">Tudnivalókat</a></li>
                </ul>
                Ha már regisztráltál, akkor fontos, hogy részletesen feltöltsd a személyes oldaladat, hiszen így tudsz bemutatkozni és elérhetővé válni mások számára, akik kapcsolatba szeretnének lépni veled.<br>
                Ha elakadnál a regisztrációnál vagy bármilyen kérdésed, észrevételed adódik akár most, akár a regisztráció után, a <a href="{{ url('/') }}/kapcsolat " target="_blank">Kapcsolat</a> oldalon elérsz bennünket és segítünk.<br>
                <br>
            </div>
            <div class="panel panel-default">
            	<div class="panel-heading">Regisztráció</div>
                <div class="panel-body">


                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Név</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-mail cím</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Jelszó</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Jelszó megerősítés</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"><input type="checkbox" name="accept" required style="width:20px;height:20px;"></label>

                            <div class="col-md-6" style="padding-top: 10px; font-size: 18px;">
                                Elolvastam és elfogadom a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodásban</a> foglaltakat.
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"><input type="checkbox" name="accept" required style="width:20px;height:20px;"></label>

                            <div class="col-md-6" style="padding-top: 10px; font-size: 18px;">
                                Elolvastam és elfogadom az <a href="{{ url('/') }}/adatkezeles " target="_blank">Adatkezelésben</a> foglaltakat.
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block" style="color: #a94442;">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Regisztráció
                                </button>
                            </div>
                        </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
