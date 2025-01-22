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
                <p style="text-align: center;"><img src="{{ url('/images/podcasts') }}/{{ $podcast->id}}.jpg" style="max-width: 50%;max-height: 400px;"></p>
                <p>{{ $podcast->body }}</p>
                <audio controls>
                    <source src="{{url('/audio/podcasts')}}/{{$podcast->url}}" type="audio/mpeg">
                </audio>
                <br>
                @if(isset($event))
                Kapcsolódó tematikus beszélgetés: <a href="{{ url('esemeny',$event->id) }}/{{$event->slug}}" target="_blank">{{ $event->title }}</a><br>
                @endif
                @if(isset($group))
                    Kapcsolódó csoport: <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}" target="_blank">{{ $group->name }}</a><br>
                @endif
            </div>
            @if($i!=$num-1)<hr>@endif
        @endfor