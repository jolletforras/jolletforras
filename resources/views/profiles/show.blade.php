@extends('layouts.app')

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
					{!! nl2br($user->introduction) !!}<br>
					@include('profiles.partials.tags')<br><br>
					@if($user->intention!='')
						<b>Amiért itt vagyok a portálon:</b><br>
						{!! nl2br($user->intention) !!}<br>
					@endif
					<br>
					@if($user->interest!='')
						<b>Ez ami lelkesít, amivel a következő hónapokban foglalkozni szeretnék:</b><br>
						{!! nl2br($user->interest) !!}<br>
					@endif
					@if (Auth::check())
						@include('profiles.partials.groups')
					@endif
                </div>
            </div>
			<hr/>
@endsection