<?php $logged_in = Auth::check(); ?>
@foreach ($projects as $project)
    <?php
    $project_admin = $project->isAdmin();
    $portal_admin = $logged_in && Auth::user()->admin;
    ?>
    @if($project->isActive() && $project->approved || $project_admin || $portal_admin)
        <h3>
            <a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->title }}</a>
            @if($project->city!='')
                - <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
            @endif
        </h3>
        <p>
            @if($project_admin || $portal_admin)
                <a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit">módosít</a>
                @if(!$project->isActive()) <i>/inaktív/</i>@endif
                @if(!$project->approved) <i>/engedélyezésre vár/</i>@endif
            @endif
        </p>
        @if(file_exists(public_path('images/projects/'.$project->id.'.jpg')))
            <p style="text-align: center;"><img src="{{ url('/images/projects') }}/{{ $project->id}}.jpg?{{$project->photo_counter}}" style="max-width: 50%;"></p>
        @endif
        <p>
            @if(strlen($project->body)>800)
                {!! nl2br(mb_substr($project->body,0,800)) !!}
                <a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">... tovább</a>
            @else
                {!! nl2br($project->body) !!}
            @endif
        </p>
        @if( isset($project->user->id))
            <span class="author"><a href="{{ url('profil',$project->user->id) }}/{{$project->user->slug}}">{{ $project->user->name }}</a>, {{ $project->created_at }}</span>
        @endif
        @if (Auth::check())
            @include('projects._tags')
            <a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
            @if( $project->counter>0)
                &nbsp;&nbsp;<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->counter }} hozzászólás</a>
            @endif
        @endif
        <hr/>
    @endif
@endforeach