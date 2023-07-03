@for ($i = 0; $i < $num=$commendations->count(); $i++)
    <?php $commendation = $commendations[$i]; ?>
    @if(isset($commendation->user->id))
        <h3><a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->title }}</a></h3>
        <p>
            @if (Auth::check() && Auth::user()->id==$commendation->user->id)
                <a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit">módosít</a>
            @endif
        </p>
        {!! $commendation->body !!}<br>
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