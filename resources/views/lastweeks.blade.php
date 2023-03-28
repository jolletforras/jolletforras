@extends('layouts.app')
@section('description', 'Az elmúlt 1 hónap történései')
@section('url', 'https://tarsadalmijollet.hu/tortenesek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tortenesek" />
@endsection

@section('content')
    <div class="row narrow-page">
        <div class="col-sm-6">
            <h2>Az elmúlt 1 hónap történései</h2>
        </div>
    </div>
   	<div class="inner_box narrow-page">
    @if($users->isNotEmpty())
        <h3>Új tagok</h3>
        @include('profiles.partials.members',['type'=>'tab1'])
        <hr>
    @endif
    @if($groups->isNotEmpty())
        <h3>Új csoportok</h3>
        @include('groups._group_list')
        <hr>
    @endif
    @if($forums->isNotEmpty())
        <h3>Új fórumok</h3>
        @include('forums._list')
        <hr>
    @endif
    @if($articles->isNotEmpty())
         <h3>Új írások</h3>
         @include('articles._list')
         <hr>
    @endif
    @if($newsletters->isNotEmpty())
        <h3>Új hírlevelek</h3>
        @include('newsletters._list')
    @endif
    @if($events->isNotEmpty())
        <h3>Új események</h3>
        @include('events._list')
        <hr>
    @endif
    </div>
@endsection
