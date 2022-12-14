<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	@yield('robots','')
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="@yield('description','A Társadalmi Jóllét Portál találkozási tér mindazok számára, akik részt vesznek a társadalmi jóllétet megvalósító, emberközpontú új világ megteremtésében.')">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	@yield('canonical','')

	<meta property="og:locale" content="hu_HU" />
	<meta property="og:type" content="website">
	<meta property="og:title" content="@yield('title','Társadalmi Jóllét Portál')">
	<meta property="og:description" content="@yield('description','A Társadalmi Jóllét Portál találkozási tér mindazok számára, akik részt vesznek a társadalmi jóllétet megvalósító, emberközpontú új világ megteremtésében.')">
	<meta property="og:url" content="@yield('url','https://tarsadalmijollet.hu/')">
	<meta property="og:site_name" content="Társadalmi Jóllét Portál">

    <link rel="shortcut icon" href="{{ url('/') }}/favicon.png">
    

    <title>Társadalmi Jóllét Portál</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
 	<link href="{{ url('/') }}/css/app.css?ver=1.31" rel="stylesheet">
	@stack('styles')

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-7KFV1G88NR"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-7KFV1G88NR');
	</script>
</head>
<body id="app-layout">
	<div class="base">
		<div class="oldal_nev">
	 		<h1><a href="{{ url('/') }}">Társadalmi Jóllét Portál</a></h1>
		</div>
		@if( Auth::check())<div id="notice" data-toggle="modal" data-target="#notice-modal">Friss @if( Auth::user()->new_post>0)<span>{{ Auth::user()->new_post }}</span>@endif </div>@endif
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
                					<!-- Collapsed Hamburger -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse" id="app-navbar-collapse">
					@if(!Auth::check() || (Auth::check() && Auth::user()->status==3 && !Session::has('warning')))
						<!-- Left Side Of Navbar -->
						<ul class="nav navbar-nav" id="main-menu">
							<li><a href="{{ url('/') }}">Nyitólap</a></li>
							<li><a href="{{ url('/tarsak') }}">Társak</a></li>
                            <li><a href="{{ url('/csoportok') }}">Csoportok</a></li>
							<li><a href="{{ url('terkep/tarsak') }}">Térkép</a></li>
							<li><a href="{{ url('kezdemenyezesek') }}">Kezdeményezések</a></li>
							<li><a href="{{ url('forum') }}">Fórum</a></li>
							<li><a href="{{ url('irasok') }}">Írások</a></li>
							<li><a href="{{ url('hirek') }}">Hírek</a></li>
							<li><a href="{{ url('esemenyek') }}">Események</a></li>
							<li class="width-wide"><a href="">Olvasd el</a>
								<ul>
									<li><a href="{{ url('kozossegimegallapodas') }}">Közösségi megállapodás</a></li>
									<li><a href="{{ url('tudnivalok') }}">Tudnivalók</a></li>
									<li><a href="{{ url('hirlevelek') }}">Hírlevelek</a></li>
								</ul>
							</li>
							<li class="width-narrow"><a href="{{ url('kozossegimegallapodas') }}">Közösségi megállapodás</a></li>
							<li class="width-narrow"><a href="{{ url('tudnivalok') }}">Tudnivalók</a></li>
							<li><a href="{{ url('kapcsolat') }}">Kapcsolat</a></li>

							@if(false && Auth::check() && Auth::user()->admin)
								<li><a href="{{ url('jovahagyra_var') }}">Jóváhagy</a></li>
							@endif
						</ul>
					@endif

					<!-- Right Side Of Navbar -->
					<ul class="nav navbar-nav navbar-right" id="profile-menu">
						<!-- Authentication Links -->
						@if (Auth::guest())
							<li><a href="{{ route('login') }}">Belépés</a></li>
							<li><a href="{{ route('register') }}">Regisztráció</a></li>
						@else
							<li class="dropdown width-wide">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									{{ Auth::user()->name }} <span class="caret"></span>
								</a>

								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
											<i class="fa fa-btn fa-sign-out"></i>Kilépek
										</a>
									</li>
									<li>
										<a href="{{ url('/profil') }}/{{ Auth::user()->id }}/{{ Auth::user()->slug }}">
											<i class="fa fa-btn fa-user"></i>Adatlapom
										</a>
									</li>
								</ul>
							</li>
							<li class="width-narrow"><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-btn fa-sign-out"></i>Kilépek</a></li>
							<li class="width-narrow"><a href="{{ url('/profil') }}/{{ Auth::user()->id }}/{{ Auth::user()->slug }}"><i class="fa fa-btn fa-user"></i>Adatlapom</a></li>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
								@csrf
							</form>
						@endif
					</ul>
				</div>
			</div>
		</nav>


		<div class="main">
			@if(Session::has('status_msg'))
				<div class="alert alert-warning">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<span style="font-size: 18px;">
						{{ Session::get('status_msg') }}
					</span>
				</div>
			@endif
			@if(Session::has('message'))
				<div class="alert alert-info" style="display: block">
					{!!  Session::get('message') !!}
				</div>
			@endif

			@if( Auth::check())
			<div class="modal fade" id="notice-modal" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Friss történések</h4>
						</div>
						<div class="modal-body">
							@foreach(\App\Models\Notice::findNew()->get() as $notice)
								@if($notice->type=="Forum")
									@if($forum = \App\Models\Forum::find($notice->notifiable_id))
										@if($notice->new>0)
								<b><a href="{{url('csoport')}}/{{$forum->group->id}}/{{$forum->group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{$forum->group->name}} - "{{ $forum->title }}" téma <span>{{$notice->new}}</span></a></b>
										@else
								<a href="{{url('csoport')}}/{{$forum->group->id}}/{{$forum->group->slug}}/tema/{{ $forum->id }}/{{$forum->slug}}">{{$forum->group->name}} - "{{ $forum->title }}" téma</a>
										@endif
									@endif
								@endif
								@if($notice->type=="Event")
									@if($event = \App\Models\Event::find($notice->notifiable_id))
										@if($notice->new>0)
								<b><a href="{{url('esemeny')}}/{{ $event->id }}/{{$event->slug}}">{{$event->group->name}} - "{{ $event->title }}" esemény <span>{{$notice->new}}</span></a></b>
										@else
								<a href="{{url('esemeny')}}/{{ $event->id }}/{{$event->slug}}">{{$event->group->name}} - "{{ $event->title }}" esemény</a>
										@endif
									@endif
								@endif
								<br>
								<hr>
							@endforeach
						</div>
						<div class="modal-footer"></div>
					</div>

				</div>
			</div>
			@endif

			@yield('content')


	    </div>
		<footer class="footer">
			<div class="left"><a href="{{ url('/adatkezeles') }}">Adatkezelési tájékoztató</a></div>
			<div class="center"><a href="mailto:tarsadalmi.jollet@gmail.com">tarsadalmi.jollet@gmail.com</a></div>
			<div class="right">© 2022 Társadalmi Jóllét Portál</div>
		</footer>
	</div>



    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}



    @yield('footer')




</body>
</html>
