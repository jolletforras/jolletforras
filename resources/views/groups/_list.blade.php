@foreach ($groups as $group)
    <div class="col-12">
        <h3>
            <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
            @if($group->city!='')
                - <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
            @endif
        </h3>
        <p style="text-align: center;">
            @if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
                <img src="{{ url('/images/groups') }}/{{$group->id}}.jpg" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;" class="img-responsive">
            @else
                <img src="{{ url('/images') }}/tarsadalmijollet.png" style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;" class="img-responsive">
            @endif
        </p>
        @if(isset($group->user->id))
            <p>
                @if(strlen($group->description)>800)
                    {!! nl2br(mb_substr($group->description,0,800)) !!}
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">... tovább</a>
                @else
                    {!! nl2br($group->description) !!}
                @endif
            </p>
            @if (Auth::check())
                @include('partials.tags',['url'=>'csoport','obj'=>$group])
            @endif
        @endif
    </div>
    <hr>
@endforeach