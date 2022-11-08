@unless($group->admins->isEmpty())
    <p>
        <b>Csoport kezelők: </b>
        @for ($i = 0; $i < $last=count($group->admins)-1; $i++)
            <a href="{{ url('profil',$group->admins[$i]->id) }}/{{$group->admins[$i]->slug}}">
                {{$group->admins[$i]->name}}</a>,&nbsp;
        @endfor
        <a href="{{ url('profil',$group->admins[$last]->id) }}/{{$group->admins[$last]->slug}}">
            {{$group->admins[$last]->name}}
        </a>
    </p>
@endunless