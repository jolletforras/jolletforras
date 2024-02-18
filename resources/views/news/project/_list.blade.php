<?php $newss = $projectnewss; ?>
@for ($i = 0; $i < $num=$newss->count(); $i++)
    <?php $news = $newss[$i]; ?>
    <h3>
        <a href="{{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}">{{ $news->title }}</a> -
        <i>@if (Auth::check() || $news->project->public)<a href="{{ url('kezdemenyezes',$news->project->id) }}/{{$news->project->slug}}">{{ $news->project->title }}</a>@else{{$news->project->title}}@endif</i>
    </h3>
    @if ($news->project->isAdmin())
        <a href="{{url('kezdemenyezes')}}/hir/{{$news->id}}/{{$news->slug}}/modosit" class="btn btn-default">módosít</a>
    @endif
    <article>
        <div class="body">{!!$news->body !!}</div>
        @include('partials.author', ['author'=>'','obj'=>$news])
    </article>
    @if($i!=$num-1)<hr>@endif
@endfor