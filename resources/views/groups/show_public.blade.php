@extends('layouts.app')
@section('title'){{ $group->name }}@endsection
@section('description'){{ $group->meta_description }}@endsection
@section('url'){{url('csoport')}}/{{$group->id}}/{{$group->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}"  />
@section('image'){{ url('/images/groups') }}/{{ $group->id.'.jpg'}}@endsection

@section('content')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		<h2>
			{{ $group->name }}
			@if($group->city!='')
				- <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
			@endif
		</h2>
		@if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
			<p style="text-align: center;"><img src="{{ url('/images/groups') }}/{{ $group->id}}.jpg" style="max-width: 50%;"></p>
		@endif
		<p>{!! nl2br($group->description) !!}</p>
		@if($group->webpage_url!='')
			<p><b>Weboldal:</b> <a href="{{$group->webpage_url}}" target="_blank">{{$group->webpage_name}}</a></p>
		@endif
		@if($group->agreement!='')
			<p>
				<b>Csoport megállapodás:</b><br>
				{!! nl2br($group->agreement) !!}
			</p>
		@endif
		<p><i>Létrehozva: {{ $group->created_at }}, módosítva:  {{ $group->updated_at }}</i></p>
		<p style="color: #337ab7; font-weight: bold;">A csoport csatlakozáshoz lépj be vagy regisztrálj az oldalon!</p>
	</div>
@endsection
