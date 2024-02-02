@unless($commendation->tags->isEmpty())
<p>
<b>Címkék: </b>
@for ($i = 0; $i < $last=count($commendation->tags)-1; $i++)
<a href="{{ url('ajanlo')}}/cimke/{{$commendation->tags[$i]->id }}/{{$commendation->tags[$i]->slug}}">{{ $commendation->tags[$i]->name }}</a>,
@endfor
<a href="{{ url('ajanlo')}}/cimke/{{$commendation->tags[$last]->id }}/{{$commendation->tags[$last]->slug}}">{{ $commendation->tags[$last]->name }}</a>
</p>
@endunless