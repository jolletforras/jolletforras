@for ($i = 0; $i < $num=$forums->count(); $i++)
    <?php $forum = $forums[$i]; ?>
    @if(isset($forum->user->id))
        <h3><a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}">{{ $forum->title }}</a></h3>
        <p>
            <span class="author"><a href="{{ url('profil',$forum->user->id) }}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>,	{{ $forum->created_at }}</span>
            @if (Auth::user()->id==$forum->user->id)
                <a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit">módosít</a>
            @endif
        </p>
        {!! $forum->body !!}
        @include('partials.tags',['url'=>'forum','obj'=>$forum])
        <a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
        @if( $forum->counter>0)
            &nbsp;&nbsp;<a href="{{ url('forum',$forum->id) }}/{{$forum->slug}}">{{ $forum->counter }} hozzászólás</a>
        @endif
        @if($i!=$num-1)<hr>@endif
    @endif
@endfor