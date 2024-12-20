        @for ($i = 0; $i < $num=$podcasts->count(); $i++)
            <?php
            $podcast = $podcasts[$i];
            $event = $podcast->event;
            $group = $podcast->group;
            ?>
            <h3><a href="{{url('az-uj-vilag-hangjai')}}/{{$podcast->id}}/{{$podcast->slug}}">{{ $podcast->title }}</a></h3>
            @if (Auth::check() && Auth::user()->admin)
                <a href="{{url('podcast')}}/{{$podcast->id}}/{{$podcast->slug}}/modosit">módosít</a>
            @endif
            <div class="body">
                <iframe class="podcast-iframe" src="{{$podcast->url}}" style="height:100%;width:100%;" frameborder="0" scrolling="no"></iframe>
                @if(isset($event))
                Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a><br>
                @endif
                @if(isset($group))
                    Kapcsolódó csoport: <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}" target="_blank">{{ $group->name }}</a><br>
                @endif
            </div>
            @if($i!=$num-1)<hr>@endif
        @endfor