@extends('layouts.app')
@section('description', 'Társak a Társadalmi Jóllét Portálon. Nyilvános bemutatkozások. A további bemutatkozásokat belépés vagy regisztráció után éred el. Csatlakozz hozzánk, várunk!')
@section('url', 'https://tarsadalmijollet.hu/tarsak')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tarsak" />
@endsection

@section('content')
	@include('profiles.partials.profile_menu')
	@if (Auth::check() && Auth::user()->id==$user->id)
	<div class="profil_almenu">
		<a href="{{ url('/profilom') }}/modosit">Adatok</a>
		<a href="{{ url('/profilom') }}/feltolt_profilkep">Profilkép</a>
		<a href="{{ url('/profilom') }}/jelszocsere">Jelszó</a>
		@if($user->status==3 || $user->status==4)
			<a href="{{ url('/profilom') }}/beallitasok">Beállítások</a>
		@endif
	</div>
	@endif
    <div class="panel panel-default">
        <div class="panel-body">
			@include('profiles.partials.show_profilephoto')
			@if (Auth::check())
				<b>Email: </b>{{ $user->email }}<br><br>
			@endif
			<b>Lakóhely:</b>
                @if($user->city=="Budapest")
				    @if($user->location!=''){{ $user->location }} @else {{ $user->city }} @endif
                @else
                    @if($user->location!='' && $user->location!=$user->city){{ $user->location }}, @endif
                    {{ $user->city }}
                @endif
				<br><br>
			<b>Legutóbb módosítva: </b>{{ $user->updated_at->format('Y-m-d') }}<br><br>
			@if($user->webpage_url!='')
				<b>Weboldal:</b> <a href="{{$user->webpage_url}}" target="_blank">{{$user->webpage_name}}</a><br><br>
			@endif
			<b>Bemutatkozás:</b><br>
			{!! $user->introduction !!}<br>
			@include('profiles.partials.tags')<br><br>
			@if($user->intention!='')
				<b>Amiért itt vagyok a portálon:</b><br>
				{!! $user->intention !!}<br>
			@endif
			<br>
			@if($user->interest!='')
				<b>Ez ami lelkesít, amivel a következő hónapokban foglalkozni szeretnék:</b><br>
				{!! $user->interest !!}<br>
			@endif
			@if (Auth::check())
				@include('profiles.partials.groups')
			@endif
        </div>
    </div>
	<hr/>
	@if(Auth::check() && Auth::user()->id != $user->id)
	<div class="message-box">
		<div class="alert alert-info" style="display: none;" id="message-alert">A üzeneted sikeresen elküldted!</div>
		<div class="form-group">
			<textarea class="form-control" rows="4" id="message" name="message" placeholder="Ide írva üzenetet küldhetsz neki"></textarea>
		</div>
		<div class="form-group">
			<button type="button" onclick="send()">Küldés</button>
		</div>
	</div>
	@endif
@endsection

@section('footer')
	<script type="text/javascript">
		function send(){
			var message = $("#message").val();

			if(message!="") {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

				$.ajax({
					type: "POST",
					url: '{{ url('send_message') }}',
					data: {
						_token: CSRF_TOKEN,
						receiver_id: "{{$user->id}}",
						message: message
					},
					success: function(data) {
						if(data['status']=='success') {
							$("#message-alert").show().delay(3000).hide("slow");
							$("#message").val("");
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		}
	</script>
@endsection