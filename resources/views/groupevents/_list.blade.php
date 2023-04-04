@for ($i = 0; $i < $num=$events->count(); $i++)
    <?php $event = $events[$i]; ?>
    <h3><a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->title }}</a></h3>
    @if ($event->isEditor() || $group->isAdmin())
        <a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" class="btn btn-default">módosít</a>
    @endif
    <article>
        <div class="body">
            @if(isset($event->image))
            <img class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;" src="{{ url('/images/posts') }}/{{$event->image}}"/>
            @endif
            @if(isset($event->shorted_text))
                {!! str_replace("#...#","<a href='".url('esemeny')."/".$event->id."/".$event->slug."'>... tovább</a>",$event->shorted_text) !!}
            @else
                {!! preg_replace("/<img[^>]+\>/i", "",$event->body) !!}
            @endif
        </div>
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