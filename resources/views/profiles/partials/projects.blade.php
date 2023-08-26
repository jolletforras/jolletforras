@unless($user->projects->isEmpty())
    <h4>
        <b>Kezdeményezéseim: </b>
        @for ($i = 0; $i < $last=count($user->projects)-1; $i++)
            <a href="{{ url('iras',$user->projects[$i]->id) }}/{{$user->projects[$i]->slug}}" target="_blank">{{$user->projects[$i]->title}}</a>,&nbsp;
        @endfor
        <a href="{{ url('iras',$user->projects[$last]->id) }}/{{$user->projects[$last]->slug}}" target="_blank">{{$user->projects[$last]->title}}</a>
    </h4>
@endunless