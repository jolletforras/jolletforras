@unless($obj->tags->isEmpty())
<p class="tags">
<b>Címkék: </b>
@for ($i = 0; $i < $last=count($obj->tags)-1; $i++)
<a href="{{ url('/')}}/{{$url}}/cimke/{{$obj->tags[$i]->id }}/{{$obj->tags[$i]->slug}}">{{ $obj->tags[$i]->name }}</a>,
@endfor
<a href="{{ url('/')}}/{{$url}}/cimke/{{$obj->tags[$last]->id }}/{{$obj->tags[$last]->slug}}">{{ $obj->tags[$last]->name }}</a>
</p>
@endunless