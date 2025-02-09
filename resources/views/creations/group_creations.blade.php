@extends('layouts.app')
@section('description')Alkotások a Jóllét Forrás oldalon a {{$group->name}} csoportban. Nézd meg a Jóllét Forrás oldal tagjainak alkotásait, csatlakozz hozzánk és vedd fel a saját alkotásod! Várunk!@endsection
@section('url')https://jolletforras.hu/csoport/{{$group->id}}/{{$group->slug}}/alkotasok @endsection
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/csoport/{{$group->id}}/{{$group->slug}}/alkotasok" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2>Alkotások - {{$group->name}}</h2>
        </div>
    </div>

    <div class="inner_box narrow-page" style="margin-top: 6px;">
        @include('creations._list')
    </div>
@endsection