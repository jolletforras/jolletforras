@for ($i = 0; $i < $num=$newss->count(); $i++)
    <?php $news = $newss[$i]; ?>
    <h3>
        <a href="/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a> -
        <i>@if (Auth::check() || $news->group_is_public)<a href="{{ url('csoport',$news->group_id) }}/{{$news->group_slug}}">{{ $news->group_name }}</a>@else{{$news->group_name}}@endif</i>
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