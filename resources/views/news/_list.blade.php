@for ($i = 0; $i < $num=$newss->count(); $i++)
    <?php $news = $newss[$i]; ?>
    <h3>
        <a href="/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a> -
        <i>@if (Auth::check() || $news->group->public)<a href="{{ url('csoport',$news->group->id) }}/{{$news->group->slug}}">{{ $news->group->name }}</a>@else{{$news->group->name}}@endif</i>
    </h3>
    <br>
    @if ($news->group->isAdmin())
        <a href="{{url('hir')}}/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
    @endif
    <article>
        <div class="body">{!!$news->body !!}</div>
        @include('partials.author', ['author'=>'','obj'=>$news])
    </article>
    @if (Auth::check())
        @include('partials.tags',['url'=>'hir','obj'=>$news])
    @endif
    @if($i!=$num-1)<hr>@endif
@endfor