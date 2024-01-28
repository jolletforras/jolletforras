@extends('layouts.app')

@section('content')
<div class="container">
    <br/>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Beállítások</div>
                <div class="panel-body">
                    <br>
                    <form method="POST" action="{{url('profilom/beallitasok')}}" accept-charset="UTF-8" class="form">

                        @csrf

                        Kérek emailben értesítést:
                        <div class="form-group" style="padding-left: 10px;">
                            <input name="my_post_comment_notice" type="checkbox" value="1" @if($user->my_post_comment_notice) checked @endif>
                            ha hozzászólnak valamelyik bejegyzésemhez <i>(írás, alkotás, csoport téma, esemény, kezdeményezés, ajánló)</i>
                        </div>

                        <div class="form-group" style="padding-left: 10px;">
                            <input name="new_post_notice" type="checkbox" value="1" @if($user->new_post_notice) checked @endif>
                            ha valamelyik csoportomban új beszélgetést/tudástárt hoznak létre*
                        </div>

                        <div class="form-group" style="padding-left: 10px;">
                            <input name="theme_comment_notice" type="checkbox" value="1" @if($user->theme_comment_notice) checked @endif>
                            azoknál a csoport témáknál <i>(beszélgetés, közlemény, tudástár)</i>, amelyekhez innentől hozzászólok és utána új hozzászólás érkezik
                        </div>

                        <div class="form-group">
                            <input name="deactivate" type="checkbox" value="1" @if($deactivate) checked @endif>
                            deaktiválom a profilom <i>(mások számára a profilom nem látható)</i>
                        </div>
                        <hr>
                        * Csoport esemény, közlemény létrehozáskor minden csoporttag kap emailben értesítést.<br/>
                        <br/>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Mentés">
                            <a href="{{url('profil')}}/{{Auth::user()->id}}/{{Auth::user()->slug}}" type="submit" class="btn btn-primary">Mégse</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
