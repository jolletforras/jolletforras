@extends('layouts.app')
@section('description', 'Ajánlók a Társadalmi Jóllét Portálon. Olvasni, nézni, hallgatni valókat találsz itt, egy felvezető ajánlással.')
@section('url', 'https://tarsadalmijollet.hu/ajanlo')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/ajanlo" /> @endsection
@section('image'){{ url('/images/ajanlo.jpeg')}}@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-5">
			<h2>Ajánló @if(isset($group))- {{$group->name}}@endif</h2>
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
			@if (Auth::check())
			<a href="#commendation_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('ajanlo')}}/uj" type="submit" class="btn btn-default">Új ajánló</a>
			@endif
		</div>
	</div>
	<div class="inner_box narrow-page collapse" id="commendation_info" style="font-size: 18px">
		Itt lehetőséged mások figyelmébe ajánlani valamit.<br>
		Mielőtt megnyitsz egy új ajánlót, tedd fel magadnak a kérdést: Hogyan kapcsolódik ez a Portál céljához és alapértékeihez? Ha nem vagy biztos a dolgodban, inkább olvasd el újra a <a href="{{ url('/') }} " target="_blank">Nyitólapot</a> és
		a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodást</a>, vagy kérj segítséget a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> csoporttól.<br>
	</div>
	@guest
	<div class="inner_box narrow-page">
		Az ajánló egy olyan lehetőség, ahol a Portál tagjai olyasmit tudnak megmutatni, ami szerintük érték az új világ építői számára. Lehet ez olvasni, nézni, hallgatni való tartalom.
		Arról, hogyan hozhatsz létre új ajánlót, <a href="{{url('tudnivalo')}}/8/ajanlo">ITT</a> találsz erről részletes tudnivalókat.<br>
		<br>
		Szeretnél értesülni új ajánlókról?<br>
		- Ha van kedved, regisztrálj a Portálon és lépj be ebbe a térbe. Kezdd <a href="{{url('register')}}">ITT</a>.<br>
		- Ha még csak távolabbról ismerkednél, iratkozz fel a hírlevelünkre <a href="https://forms.gle/S18g4L3TAPC9ZMe99">ITT</a>.<br>
	</div>
	@endguest
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('commendations._list')
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'ajanlo'])
@endsection
