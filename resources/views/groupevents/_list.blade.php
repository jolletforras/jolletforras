@for ($i = 0; $i < $num=$events->count(); $i++)
    <?php $event = $events[$i]; ?>
    <h3><a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->title }}</a></h3>
    @if ($event->isEditor() || $group->isAdmin())
        <a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" class="btn btn-default">módosít</a>
    @endif
    <article>
        <div class="body">{!! $event->body !!}</div>
    </article>
    @if (Auth::check())
        @if($actual)
            <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
        @endif
        @if( $event->counter>0)
            &nbsp;&nbsp;<a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->counter }} hozzászólás</a>
        @endif
    @endif
    @if($i!=$num-1)<hr>@endif
@endfor