@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-5">
			<h2>Kezdeményezések @if(isset($group))- {{$group->name}}@endif</h2>
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
	<hr style="margin-top:6px;">
	<div class="row">
		<?php $i=1; ?>
	@foreach ($projects as $project)
		<div class="col-12 col-sm-6 col-md-4 group">
			<div class="card">
				<div class="card-header"></div>
				<div class="card-title">
					<h3>
						<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->title }}</a>
						@if($project->city!='')
							- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
						@endif
					</h3>
				</div>
				<div class="image-box">
					@if(file_exists(public_path('images/projects/'.$project->id.'.jpg')))
						<div class="image" style="background-image:url('{{url('images')}}/projects/{{$project->id}}.jpg?{{$project->photo_counter}}');"></div>
					@else
						<div class="image" style="background-image:url('{{url('images')}}/tarsadalmijollet.png');"></div>
					@endif
				</div>
				<div class="card-body">
				@if(isset($project->user->id))
					<div>
						@if(strlen($project->body)>800)
							{!! nl2br(mb_substr($project->body,0,800)) !!}
							<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">... tovább</a>
						@else
							{!! nl2br($project->body) !!}
						@endif
					</div>
					@if (Auth::check())
						@include('projects._tags')
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
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'kezdemenyezes'])
@endsection