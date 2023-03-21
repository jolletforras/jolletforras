
<div class="panel panel-default">
    <div class="panel-body">
        @for ($i = 0; $i < $num=$groups->count(); $i++)
            <?php $group = $groups[$i]; ?>
            @if(isset($group->user->id))
                <h3>
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
                    @if($group->city!='')
                        - <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
                    @endif
                </h3>
                <p>
                @if(Auth::check() && strlen($group->description)>800)
                    {!! nl2br(mb_substr($group->description,0,800)) !!}
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">... tovább</a>
                @else
                    {!! nl2br($group->description) !!}
                @endif
                @if (Auth::check())
                    @include('partials.tags',['url'=>'csoport','obj'=>$group])
                @endif
                @if($i!=$num-1)<hr>@endif
            @endif
        @endfor
    </div>
</div>
