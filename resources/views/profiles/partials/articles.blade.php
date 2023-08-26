@unless($user->articles->isEmpty())
    <h4>
        <b>Írásaim: </b>
        @for ($i = 0; $i < $last=count($user->articles)-1; $i++)
            <a href="{{ url('iras',$user->articles[$i]->id) }}/{{$user->articles[$i]->slug}}" target="_blank">{{$user->articles[$i]->title}}</a>,&nbsp;
        @endfor
        <a href="{{ url('iras',$user->articles[$last]->id) }}/{{$user->articles[$last]->slug}}" target="_blank">{{$user->articles[$last]->title}}</a>
    </h4>
@endunless