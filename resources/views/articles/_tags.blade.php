@unless($article->tags->isEmpty())
<p>
    <b>Címkék: </b>
    @for ($i = 0; $i < $last=count($article->tags)-1; $i++)
    <a href="{{ url('iras')}}/cimke/{{$article->tags[$i]->id }}/{{$article->tags[$i]->slug}}">{{ $article->tags[$i]->name }}</a>,
    @endfor
    <a href="{{ url('iras')}}/cimke/{{$article->tags[$last]->id }}/{{$article->tags[$last]->slug}}">{{ $article->tags[$last]->name }}</a>
</p>
@endunless