@unless($group->tags->isEmpty())
<p>
<b>Címkék: </b>
@for ($i = 0; $i < $last=count($group->tags)-1; $i++)
<a href="{{ url('csoport')}}/cimke/{{$group->tags[$i]->id }}/{{$group->tags[$i]->slug}}">{{ $group->tags[$i]->name }}</a>,
@endfor
<a href="{{ url('csoport')}}/cimke/{{$group->tags[$last]->id }}/{{$group->tags[$last]->slug}}">{{ $group->tags[$last]->name }}</a>
</p>
@endunless