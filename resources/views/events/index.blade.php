@extends('layouts.app')
@section('description', 'Események a Társadalmi Jóllét Portálon. Programok, fórumok, lehetőségek, amelyek a társadalmi jóllét megvalósítását segítik. Nézz szét, regisztrálj, kapcsolódj!')
@section('url', 'https://tarsadalmijollet.hu/esemenyek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/esemenyek" />
@endsection

@section('content')
	<div class="row narrow-page">
		<div class="col-sm-9">
			<h2>Események</h2>@if (Auth::check())<a href="#events_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>@endif
		</div>
		<div class="col-sm-3 text-right">
			@if (Auth::check())
				<a href="#create_events_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('esemeny')}}/uj" type="submit" class="btn btn-default">Esemény felvétele</a>
			@endif
		</div>
	</div>
	@if (Auth::check())
	<div class="inner_box collapse narrow-page" id="events_info">
			Ezen az oldalon találod a regisztrált tagok és a csoportok nyilvános eseményeit.
	</div>
	@endif
	@guest
	<div class="inner_box narrow-page">
		A Társadalmi Jóllét Portálon létrehozhatod és kommunikálhatod az eseményedet, magánszemélyként vagy egy csoport eseményeként.<br>
		<a href="{{url('tudnivalo')}}/10/esemenyek">ITT</a> találsz erről részletes leírást.<br>
		<br>
		Szeretnél értesülni új eseményekről?<br>
		- Ha van kedved, regisztrálj a Portálon és lépj be ebbe a térbe. Kezdd <a href="{{url('register')}}">ITT</a>.<br>
		- Ha még csak távolabbról ismerkednél, iratkozz fel a hírlevelünkre <a href="https://forms.gle/S18g4L3TAPC9ZMe99">ITT</a>.<br>
	</div>
	@endguest
	@include('events._create_events_info')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@include('events._list',['events'=>$events])
		<hr>
		<button class="btn btn-default" id="events_expired_btn" onclick="loadEventsExpired()"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Lejárt események</button>
		<div id="events_expired"></div>
	</div>
@endsection

@section('footer')
	<script type="text/javascript">
		function loadEventsExpired(){
			$("#events_expired").html('... hamarosan megjelenik');

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{ url('events_expired') }}',
				data: {
					_token: CSRF_TOKEN
				},
				success: function(data) {
					if(data['status']=='success') {
						$("#events_expired_btn").hide();
						$("#events_expired").html(data['content_html']);
					}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}
	</script>
@endsection