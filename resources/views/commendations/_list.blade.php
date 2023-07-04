@for ($i = 0; $i < $num=$commendations->count(); $i++)
    <?php
        $commendation = $commendations[$i];
        $my_post = Auth::check() && Auth::user()->id==$commendation->user->id ? true : false;
    ?>
    @if(isset($commendation->user->id) && ($commendation->active || $my_post))
        <h3><a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->title }}</a></h3>
        <p>
            @if($my_post)
                <a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit">módosít</a>
                @if(!$commendation->active) <i>inaktív</i>@endif
            @endif
        </p>
        {!! $commendation->body !!}<br>
        @if(!empty($commendation->url))
        <a href="{!! $commendation->url !!}" target="_blank">{!! substr($commendation->url,0,50) !!}</a><br>
        @endif
        <span class="author"><a href="{{ url('profil',$commendation->user->id) }}/{{$commendation->user->slug}}">{{ $commendation->user->name }}</a>,	{{ $commendation->created_at }}</span><br>
        @if (Auth::check())
            <a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
            @if( $commendation->counter>0)
                &nbsp;&nbsp;<a href="{{ url('commendation',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->counter }} hozzászólás</a>
            @endif
            @if($i!=$num-1)<hr>@endif
        @endif
    @endif
@endfor