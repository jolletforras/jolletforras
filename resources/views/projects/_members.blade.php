@unless($project->members->isEmpty())
    <p>
        <b>Résztvevők: </b>
        @for ($i = 0; $i < $last=count($project->members)-1; $i++)
            <a href="{{ url('profil',$project->members[$i]->id) }}/{{$project->members[$i]->slug}}">
                {{$project->members[$i]->name}}</a>,&nbsp;
        @endfor
        <a href="{{ url('profil',$project->members[$last]->id) }}/{{$project->members[$last]->slug}}">
            {{$project->members[$last]->name}}
        </a>
    </p>
@endunless