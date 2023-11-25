@extends('layouts.app')

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