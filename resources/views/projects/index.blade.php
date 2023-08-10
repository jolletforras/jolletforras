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
					<h3><a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->title }}</a></h3>
					<p>
						<a href="{{ url('profil',$project->user->id) }}/{{$project->user->slug}}">{{ $project->user->name }}</a>, {{ $project->created_at }}
						@if (Auth::check() && Auth::user()->id==$project->user->id)
							<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit">módosít</a>
						@endif
					</p>
					<p>{{ $project->body }}</p>
					@if (Auth::check())
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
location.href="{{ url('kezdemenyezes')}}/ertes/"+id+"/"+tags[id];
});
</script>
@endsection