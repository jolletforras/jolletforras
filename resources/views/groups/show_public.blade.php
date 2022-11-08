@extends('layouts.app')
@section('description', 'Csoportok a Társadalmi Jóllét Portálon. Nyilvánosan elérhető bemutatkozások. Csatlakozz csoporthoz vagy hozd létre itt közösséged online csoportját! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/csoportok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/csoportok" />
@endsection

@section('content')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		<h2>
			{{ $group->name }}
			@if($group->city!='')
				- <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
			@endif
		</h2>
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
	</div>
@endsection
