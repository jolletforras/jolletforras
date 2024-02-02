@unless($project->tags->isEmpty())
<p>
<b>Címkék: </b>
@for ($i = 0; $i < $last=count($project->tags)-1; $i++)
<a href="{{ url('kezdemenyezes')}}/cimke/{{$project->tags[$i]->id }}/{{$project->tags[$i]->slug}}">{{ $project->tags[$i]->name }}</a>,
@endfor
<a href="{{ url('kezdemenyezes')}}/cimke/{{$project->tags[$last]->id }}/{{$project->tags[$last]->slug}}">{{ $project->tags[$last]->name }}</a>
</p>
@endunless