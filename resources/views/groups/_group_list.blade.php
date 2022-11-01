
<div class="panel panel-default">
    <div class="panel-body">
        @for ($i = 0; $i < $num=$groups->count(); $i++)
            <?php $group = $groups[$i]; ?>
            @if(isset($group->user->id))
                <h3>
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
                    @if($group->city!='')
                        - <i style="font-weight: normal; font-size: 16px;">{{$group->location()}}</i>
                    @endif
                </h3>
                @if ($group->isAdmin())
                    <p><a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a></p>
                @endif
                <p>
                @if(Auth::check() && strlen($group->description)>800)
                    {!! nl2br(mb_substr($group->description,0,800)) !!}
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">... tovább</a>
                @else
                    {!! nl2br($group->description) !!}
                @endif
                @if (Auth::check())
                    @include('groups._tags')
                @endif
                @if($i!=$num-1)<hr>@endif
            @endif
        @endfor
    </div>
</div>
