@unless($user->member_of_groups->isEmpty())
    <br>
    <p>
        <b>Csoportjaim: </b>
        @for ($i = 0; $i < $last=count($user->member_of_groups)-1; $i++)
            <a href="{{ url('csoport',$user->member_of_groups[$i]->id) }}/{{$user->member_of_groups[$i]->slug}}">
                {{$user->member_of_groups[$i]->name}}</a>,&nbsp;
        @endfor
        <a href="{{ url('csoport',$user->member_of_groups[$last]->id) }}/{{$user->member_of_groups[$last]->slug}}">
            {{$user->member_of_groups[$last]->name}}
        </a>
    </p>
@endunless