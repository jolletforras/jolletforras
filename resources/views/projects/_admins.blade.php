@unless($project->admins->isEmpty())
    <p>
        <b>Kezelők: </b>
        @for ($i = 0; $i < $last=count($project->admins)-1; $i++)
            <a href="{{ url('profil',$project->admins[$i]->id) }}/{{$project->admins[$i]->slug}}">
                {{$project->admins[$i]->name}}</a>,&nbsp;
        @endfor
        <a href="{{ url('profil',$project->admins[$last]->id) }}/{{$project->admins[$last]->slug}}">
            {{$project->admins[$last]->name}}
        </a>
    </p>
@endunless