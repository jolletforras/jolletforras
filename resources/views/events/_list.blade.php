@for ($i = 0; $i < $num=$events->count(); $i++)
    <?php $event = $events[$i]; ?>
    <h3><a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->title }}</a></h3>
    @if(isset($event->group))
        @if (Auth::check())
            <i>/A(z) <b><a href="{{ url('csoport',$event->group->id) }}/{{$event->group->slug}}">{{ $event->group->name }}</a></b> csoport eseménye/</i><br>
        @elseif ($event->group->public)
            <i>/A(z) <b>{{$event->group->name}}</b> csoport eseménye/</i><br>
        @endif
    @endif
    @if (Auth::check())
        @if($event->isEditor() || ($event->group_id==0 && Auth::user()->admin))
            <a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" class="btn btn-default">módosít</a>
        @endif
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
        <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
        @if( $event->counter>0)
            &nbsp;&nbsp;<a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}">{{ $event->counter }} hozzászólás</a>
        @endif
    @endif
    @if($i!=$num-1)<hr>@endif
@endfor