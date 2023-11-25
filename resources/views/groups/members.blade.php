@extends('layouts.app')
@section('title'){{ $group->name }}@endsection
@section('description'){{ $group->meta_description }}@endsection
@section('url'){{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tagok@endsection
@section('canonical')<link rel="canonical" href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tagok"  />@endsection
@section('image'){{ url('/images/groups') }}/{{ $group->id.'.jpg?'.$group->photo_counter}}@endsection

@section('content')
	@include('groups._group_menu')
	<div class="narrow-page">
		@if($users->isNotEmpty())
			@include('profiles.partials.members',['type'=>'tab1'])
		@else
			<div class="inner_box">
				Nincs nyilvánosan elérhető csoport tag
			</div>
		@endif
	</div>
@endsection