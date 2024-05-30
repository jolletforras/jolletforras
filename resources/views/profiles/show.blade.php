@extends('layouts.app')
@section('description', 'Társak a Jóllét Forrás oldalon. Nyilvános bemutatkozások. A további bemutatkozásokat belépés vagy regisztráció után éred el. Csatlakozz hozzánk, várunk!')
@section('url'){{url('profil')}}/{{$user->id}}/{{$user->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('profil')}}/{{$user->id}}/{{$user->slug}}" />
@endsection

@section('content')
	@include('profiles.partials.profile_menu')
	@if ($user->myProfile())
	<div class="profil_almenu narrow-page">
		<a href="{{ url('/profilom') }}/modosit">Adatok</a>
		<a href="{{ url('/profilom') }}/feltolt_profilkep">Profilkép</a>
		<a href="{{ url('/profilom') }}/jelszocsere">Jelszó</a>
		@if($user->status==3 || $user->status==4)
			<a href="{{ url('/profilom') }}/beallitasok">Beállítások</a>
		@endif
	</div>
	@endif
    <div class="panel panel-default narrow-page" style="margin-top: 6px;">
        <div class="panel-body introduction">
			@include('profiles.partials.show_profilephoto')
			@if (Auth::check())
				<b>Email: </b>{{ $user->email }}<br><br>
				@if(Auth::user()->admin)<b>Státusz:</b> {{ $user->status_txt }}<br><br>@endif
			@endif
			<b>Lakóhely:</b>
				{{ $user->full_location }}
			<br><br>
			@if(isset($user->updated_at))<b>Legutóbb módosítva: </b>{{ $user->updated_at->format('Y-m-d') }}<br><br>@endif
			@if($user->webpage_url!='')
				<b>Weboldal:</b> {!!$user->webpages!!}<br><br>
			@endif
			<h4><b>Bemutatkozás:</b></h4>
			{!! $user->introduction !!}
			@if($user->intention!='')
				<h4><b>Amiért itt vagyok a Jóllét Forrás oldalon:</b></h4>
				{!! $user->intention !!}
			@endif
			@if($user->interest!='')
				<h4><b>Ez ami lelkesít, amivel a következő hónapokban foglalkozni szeretnék:</b></h4>
				{!! $user->interest !!}
			@endif
			<h4><b>Jártasság, tudás:</b> @include('profiles.partials.skill_tags')</h4>
			@unless($user->interest_tags->isEmpty())<h4><b>Érdeklődés, tanulás:</b> @include('profiles.partials.interest_tags')</h4>@endunless
			@if (Auth::check())
				@include('profiles.partials.groups')
				@include('profiles.partials.articles')
				@include('profiles.partials.projects')
			@endif
        </div>
    </div>
	<hr/>
	@if(Auth::check() && Auth::user()->id != $user->id)
	<div class="message-box narrow-page">
		<div class="alert alert-info" style="display: none;" id="message-alert">A üzeneted sikeresen elküldted!</div>
		<div class="form-group">
			<textarea class="form-control" rows="4" id="message" name="message" placeholder="Ide írva üzenetet küldhetsz neki"></textarea>
		</div>
		<div class="form-group">
			<button id="send_message_btn" type="button" onclick="send()">Küldés</button><span id="message_is_sending" style="display: none; font-style: italic;"> ... üzenetküldésed folyamatban van</span>
		</div>
	</div>
	@endif
@endsection

@section('footer')
	@if(Auth::check())
	<script type="text/javascript">
		function send(){
			var message = $("#message").val();

			if(message!="") {
				$("#send_message_btn").prop('disabled', true);
				$("#message_is_sending").show();

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
							$("#message_is_sending").hide();
							$("#message-alert").show().delay(3000).hide("slow");
							$("#message").val("");
							$("#send_message_btn").prop('disabled', false);
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		}
	</script>
	@endif
@endsection