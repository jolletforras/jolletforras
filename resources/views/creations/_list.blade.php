<?php $logged_in = Auth::check(); ?>
@for ($i = 0; $i < $num=$creations->count(); $i++)
    <?php
        $creation = $creations[$i];
        $my_post = $logged_in && Auth::user()->id==$creation->user->id ? true : false;
    ?>
    @if(isset($creation->user->id) && ($creation->active || $my_post))
        <h3><a href="{{ url('alkotas',$creation->id) }}/{{$creation->slug}}">{{ $creation->title }}</a></h3>
        <p>
            @if($my_post)
                <a href="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}/modosit">módosít</a>
                @if(!$creation->active) <i>/inaktív/</i>@endif
            @endif
        </p>
        {!! nl2br($creation->body) !!}<br>
        @if(!empty($creation->url))
        <div class="inner_box" style="background-color: #fbfbfb">
            <p><a href="{{ $creation->url }}" target="_blank">{{ $creation->meta_title }}</a></p>
            <p><a href="{{ $creation->url }}" target="_blank"><img src="{{$creation->meta_image}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
            <p>@if(strlen($creation->meta_description)>300){{ mb_substr($creation->meta_description,0,300) }} ... @else {{ $creation->meta_description }} @endif</p>
        </div>

        @endif
        <span class="author">{{ $creation->created_at }}</span><br>
        @if ($logged_in)
            <a href="{{ url('alkotas',$creation->id) }}/{{$creation->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
            @if( $creation->counter>0)
                &nbsp;&nbsp;<a href="{{ url('alkotas',$creation->id) }}/{{$creation->slug}}">{{ $creation->counter }} hozzászólás</a>
            @endif
        @endif
        @if($i!=$num-1)<hr>@endif
    @endif
@endfor