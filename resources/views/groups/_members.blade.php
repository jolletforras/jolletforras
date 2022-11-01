@unless($group->members->isEmpty())
    <p>
        <b>Tagok: </b>
        @for ($i = 0; $i < $last=count($group->members)-1; $i++)
            <a href="{{ url('profil',$group->members[$i]->id) }}/{{$group->members[$i]->slug}}">
                {{$group->members[$i]->name}}</a>,&nbsp;
        @endfor
        <a href="{{ url('profil',$group->members[$last]->id) }}/{{$group->members[$last]->slug}}">
            {{$group->members[$last]->name}}
        </a>
    </p>
@endunless