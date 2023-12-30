@extends('layouts.app')
@section('description'){{$user->name}} alkotásai a Társadalmi Jóllét Portálon. Nézd meg a Portál tagjainak alkotásait, csatlakozz hozzánk és vedd fel a saját alkotásod! Várunk!@endsection
@section('url')https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/alkotasok @endsection
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/profil/{{$user->id}}/{{$user->slug}}/alkotasok" />
@endsection

@section('content')
    @include('profiles.partials.profile_menu')
    @if ($user->myProfile())
        @include('creations._new_creation_info', ['collapse'=>' collapse'])
    @endif

    <div class="inner_box narrow-page" style="margin-top: 6px;">
        @include('creations._list')
    </div>
@endsection