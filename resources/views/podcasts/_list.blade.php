        @for ($i = 0; $i < $num=$podcasts->count(); $i++)
            <?php
            $podcast = $podcasts[$i];
            $event = $podcast->event;
            ?>
            <h3>{{ $podcast->title }} </h3>
            @if (Auth::check() && Auth::user()->admin)
                <a href="{{url('podcast')}}/{{$podcast->id}}/{{$podcast->slug}}/modosit">módosít</a>
            @endif
            <div class="body">
                <iframe class="podcast-iframe" src="{{$podcast->url}}" style="height:100%;width:100%;" frameborder="0" scrolling="no"></iframe>
                Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a><br>
            </div>
            @if($i!=$num-1)<hr>@endif
        @endfor