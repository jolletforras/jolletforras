@extends('layouts.app')

@section('content')
	@include('groups._group_menu')
	<div class="narrow-page">
		@include('profiles.partials.members',['type'=>'tab1'])
	</div>
@endsection