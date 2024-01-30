@extends('layouts.app')

@section('content')
	@include('partials.tinymce_just_link_js')
	<h2>{{$title}}</h2>
	@if($type=='announcement')
		<a href="#create_anoucement_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a>
		<div class="inner_box collapse" id="create_anoucement_info" style="font-size: 18px">
			Közlemény létrehozáskor minden csoporttag kap emailben értesítést.<br/>
		</div>
	@endif
	@if($type=="knowledge" && !empty($group->knowledge_info))
		<div class="inner_box">
			<b>Így járj el a tudástár bejegyzés felvételekor:</b>
			{!! $group->knowledge_info  !!}
		</div>
	@endif
	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">
			<form method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/uj" accept-charset="UTF-8">
				@include('groupthemes._form', ['operation'=>'create','submitButtonText'=>'Mentés'])
			</form>
		</div>
	</div>
@stop