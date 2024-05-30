<div class="row">
<?php $i=1; ?>
@foreach ($groups as $group)
    <div class="col-12 col-sm-6 col-md-4 group">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-title">
                <h3>
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">{{ $group->name }}</a>
                    @if($group->city!='')
                        - <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
                    @endif
                </h3>
            </div>
            <div class="image-box">
                @if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
                <div class="image" style="background-image:url('{{url('images')}}/groups/{{$group->id}}.jpg?{{$group->photo_counter}}');"></div>
                @else
                <div class="image" style="background-image:url('{{url('images')}}/jolletforras.png');"></div>
                @endif
            </div>
            <div class="card-body">
            @if(isset($group->user->id))
                <?php $description = strip_tags($group->description);?>
                <div>
                @if(strlen($description)>800)
                    {!! mb_substr($description,0,800) !!}
                    <a href="{{ url('csoport',$group->id) }}/{{$group->slug}}">... tovább</a>
                @else
                    {!! $group->description !!}
                @endif
                </div>
                @if (Auth::check())
                    @include('partials.tags',['url'=>'csoport','obj'=>$group])
                @endif
            @endif
            </div>
        </div>
    </div>
        @if($i%3==0)
</div>
<div class="row">
        @endif
        <?php $i++ ?>
    @endforeach
</div>