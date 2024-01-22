@extends('layouts.app')
@section('title'){{ $project->title }}@endsection
@section('description'){{ $project->meta_description }}@endsection
@section('url'){{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}"  />@endsection
@section('image')@if(file_exists(public_path('images/projects/'.$project->id.'.jpg'))){{ url('/images/projects') }}/{{ $project->id.'.jpg?'.$project->photo_counter}}@else{{url('/images/tarsadalmijollet.png')}}@endif @endsection

@section('content')
	<div class="row narrow-page">
		<h2>
			{{ $project->title }}
	@if($project->city!='')
			- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
	@endif
		</h2>
	</div>
	<div class="inner_box narrow-page" style="margin-top:6px;">
	@if(file_exists(public_path('images/projects/'.$project->id.'.jpg')))
		<p style="text-align: center;"><img src="{{ url('/images/projects') }}/{{ $project->id}}.jpg?{{$project->photo_counter}}" style="max-width: 50%;"></p>
	@endif
		<p>{!! nl2br($project->body) !!}</p>
		<p>
	@if($project->user->public)
			<b>Felvette: </b><a href="{{ url('profil',$project->user->id) }}/{{$project->user->slug}}">{{ $project->user->name }}</a>, {{ $project->created_at }}
	@else
			<hr>
			Ha szeretnéd felvenni a kapcsolatot a kezdeményezővel, <a href="{{ url('login') }}">lépj be</a> vagy írj a tarsadalmi.jollet@gmal.com címre és mi összekapcsolunk a kezdeményezővel.
	@endif
		</p>
	</div>
@endsection