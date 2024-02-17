@extends('layouts.app')
@section('description', 'Az elmúlt 1 hónap történései')
@section('url', 'https://tarsadalmijollet.hu/tortenesek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tortenesek" />
@endsection

@section('content')
    <div class="row narrow-page">
        <div class="col-sm-6">
            @if (Auth::check())
                <h2>Az elmúlt 1 hónap történései</h2>
            @else
                <h2>Az elmúlt 1 hónap nyilvános történései</h2>
            @endif
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
        @foreach ($groups as $group)
        <div class="col-12">
            <h3>
                <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
                @if($group->city!='')
                    - <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
                @endif
            </h3>
            <p style="text-align: center;">
                @if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
                    <img src="{{ url('/images/groups') }}/{{$group->id}}.jpg" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;" class="img-responsive">
                @else
                    <img src="{{ url('/images') }}/tarsadalmijollet.png" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;" class="img-responsive">
                @endif
            </p>
            @if(isset($group->user->id))
            <p>
                @if(strlen($group->description)>800)
                    {!! nl2br(mb_substr($group->description,0,800)) !!}
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">... tovább</a>
                @else
                    {!! nl2br($group->description) !!}
                @endif
            </p>
                @if (Auth::check())
            @include('partials.tags',['url'=>'csoport','obj'=>$group])
                @endif
            @endif
        </div>
        <hr>
        @endforeach
    @endif
    @if($podcasts->isNotEmpty())
        <h3>Az új világ hangjai</h3>
        @include('podcasts._list')
        <hr>
    @endif
    @if($articles->isNotEmpty())
         <h3>Új írások</h3>
         @foreach ($articles as $article)
         <div class="col-12">
             <h3><a href="{{ url('iras',$article->id) }}/{{$article->slug}}">{{ $article->title }}</a></h3>
             @if (Auth::check() && (Auth::user()->id==$article->user->id || Auth::user()->admin))
                 <a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" class="edit">módosít</a><br>
             @endif
             <p style="text-align: center;"><img src="{{ url('/images/posts') }}/{{ $article->image}}" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;"></p>
             <p>{!! $article->short_description !!} <a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}">... tovább</a></p>
         </div>
        @endforeach
         <hr>
    @endif
    @if($groupnewss->isNotEmpty())
        <h3>Új csoport hírek</h3>
        @include('news.group._list')
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
    @if($commendations->isNotEmpty())
        <h3>Új ajánlók</h3>
        @include('commendations._list')
        <hr>
    @endif
    </div>
@endsection
