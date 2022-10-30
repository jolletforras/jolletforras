@unless($forum->tags->isEmpty())
<p>
<b>Címék: </b>
@for ($i = 0; $i < $last=count($forum->tags)-1; $i++)
<a href="{{ url('forum')}}/cimke/{{$forum->tags[$i]->id }}/{{$forum->tags[$i]->slug}}">{{ $forum->tags[$i]->name }}</a>,
@endfor
<a href="{{ url('forum')}}/cimke/{{$forum->tags[$last]->id }}/{{$forum->tags[$last]->slug}}">{{ $forum->tags[$last]->name }}</a>
</p>
@endunless