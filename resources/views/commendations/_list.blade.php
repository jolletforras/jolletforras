<?php $logged_in = Auth::check(); ?>
@for ($i = 0; $i < $num=$commendations->count(); $i++)
    <?php
        $commendation = $commendations[$i];
        $my_post = $logged_in && Auth::user()->id==$commendation->user->id ? true : false;
        $admin = $logged_in && Auth::user()->admin;
    ?>
    @if(isset($commendation->user->id) && ($commendation->active && $commendation->approved || $my_post || $admin))
        <h3><a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->title }}</a></h3>
        <p>
            @if($my_post || $admin)
                <a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit">módosít</a>
                @if(!$commendation->active) <i>/inaktív/</i>@endif
                @if(!$commendation->approved) <i>/nincs engedélyezve/</i>@endif
            @endif
        </p>
        {!! nl2br($commendation->body) !!}<br>
        @if(!empty($commendation->url))
        <div class="inner_box" style="background-color: #fbfbfb">
            <p><a href="{{ $commendation->url }}" target="_blank">{{ $commendation->meta_title }}</a></p>
            <p><a href="{{ $commendation->url }}" target="_blank"><img src="{{$commendation->meta_image}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
            <p>@if(strlen($commendation->meta_description)>300){{ mb_substr($commendation->meta_description,0,300) }} ... @else {{ $commendation->meta_description }} @endif</p>
        </div>

        @endif
        <span class="author"><a href="{{ url('profil',$commendation->user->id) }}/{{$commendation->user->slug}}">{{ $commendation->user->name }}</a>,	{{ $commendation->created_at }}</span><br>
        @if ($logged_in)
            <a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
            @if( $commendation->counter>0)
                &nbsp;&nbsp;<a href="{{ url('commendation',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->counter }} hozzászólás</a>
            @endif
        @endif
        @if($i!=$num-1)<hr>@endif
    @endif
@endfor