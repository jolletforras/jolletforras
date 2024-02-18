<?php $newss = $groupnewss; ?>
@for ($i = 0; $i < $num=$newss->count(); $i++)
    <?php $news = $newss[$i]; ?>
    <h3>
        <a href="{{url('csoport')}}/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a> -
        <i>@if (Auth::check() || $news->group->public)<a href="{{ url('csoport',$news->group->id) }}/{{$news->group->slug}}">{{ $news->group->name }}</a>@else{{$news->group->name}}@endif</i>
    </h3>
    @if ($news->group->isAdmin())
        <a href="{{url('csoport')}}/hir/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
    @endif
    <article>
        <div class="body">{!!$news->body !!}</div>
        @include('partials.author', ['author'=>'','obj'=>$news])
    </article>
    @if($i!=$num-1)<hr>@endif
@endfor