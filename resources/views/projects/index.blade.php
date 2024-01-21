@extends('layouts.app')

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-5">
			<h2>Kezdeményezések</h2>
		</div>
		<div class="col-sm-3" style="padding-top:4px;">
			@if(Auth::check())
			<select id="tag" name="tag" class="form-control">
				@foreach($tags as $key => $val)
					<option value="{{ $key }}">{{ $val }}</option>
				@endforeach
			</select>
			@endif
		</div>
		<div class="col-sm-4 text-right">
			@if(Auth::check())
			<a href="{{url('kezdemenyezes')}}/uj" type="submit" class="btn btn-default">Új kezdeményezés</a>
			@endif
		</div>
	</div>
	<div class="panel panel-default narrow-page">
		<div class="panel-body">
			@foreach ($projects as $project)
				@if(isset($project->user->id))
					<h3>
						<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->title }}</a>
						@if($project->city!='')
							- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
						@endif
					</h3>
					@if ($project->isAdmin())
						<p><a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit">módosít</a></p>
					@endif
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
					@if (Auth::check())
						<p><b>Felvette: </b><a href="{{ url('profil',$project->user->id) }}/{{$project->user->slug}}">{{ $project->user->name }}</a>, {{ $project->created_at }}</p>
						@include('projects._members')
						@include('projects._tags')
						<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
						@if( $project->counter>0)
							&nbsp;&nbsp;<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->counter }} hozzászólás</a>
						@endif
					@endif
        <hr/>
    @endif
@endforeach
</div>
</div>
@endsection

@section('footer')
<script>
var tags = {
@foreach ($tags_slug as $id => $slug)
{{$id}}:"{{$slug}}",
@endforeach
};

$('#tag').select2({
placeholder: 'Keresés címke szerint',
tags: false
});

$("#tag").change(function () {
var id= $("#tag").val();
location.href="{{ url('kezdemenyezes')}}/cimke/"+id+"/"+tags[id];
});
</script>
@endsection