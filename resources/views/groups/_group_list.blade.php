@foreach ($groups as $group)
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header"></div>
            <div class="image-box">
                @if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
                <div class="image" style="background-image:url('images/groups/{{$group->id}}.jpg');"></div>
                @else
                <div class="image" style="background-image:url('images/tarsadalmijollet.png');"></div>
                @endif
            </div>
            <div class="card-body">
            @if(isset($group->user->id))
                <h3>
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
                    @if($group->city!='')
                        - <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
                    @endif
                </h3>
                <p>
                @if(strlen($group->description)>700)
                    {!! nl2br(mb_substr($group->description,0,700)) !!}
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
        </div>
    </div>
@endforeach
