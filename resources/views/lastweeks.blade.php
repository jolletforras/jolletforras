@extends('layouts.app')
@section('description', 'Az elmúlt 1 hónap történései')
@section('url', 'https://tarsadalmijollet.hu/tortenesek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tortenesek" />
@endsection

@section('content')
    <h2>Az elmúlt 1 hónap történései</h2><br>
   	<div class="inner_box">
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
    <!--
        <h3>Új fórumok</h3>
        <hr>
        <h3>Új írások</h3>
        <hr>
        <h3>Új hírlevelek</h3>-->
    </div>
@endsection
