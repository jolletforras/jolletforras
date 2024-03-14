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
	<meta property="og:image" content="@yield('image','https://tarsadalmijollet.hu/images/tarsadalmijollet.png')">

    <link rel="shortcut icon" href="{{ url('/') }}/favicon.png?2">
    

    <title>Társadalmi Jóllét Portál</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
	@yield('datepicker-css','')
 	<link href="{{ url('/') }}/css/app.css?ver=1.65" rel="stylesheet">
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
		@if( Auth::check())
			<div class="notice group" data-toggle="modal" data-target="#notice-group-modal" id="notice-group">
				<i class="fa fa-users" aria-hidden="true"></i><i class="fa fa-bell-o" aria-hidden="true"></i>
				@if( Auth::user()->new_post>0)<span>{{ Auth::user()->new_post }}</span>@endif
			</div>
			<div class="notice user" data-toggle="modal" data-target="#notice-user-modal" id="notice-user">
				<i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-bell-o" aria-hidden="true"></i>
				<span id="user_new_post" @if( Auth::user()->user_new_post==0) style="display: none;"@endif>{{ Auth::user()->user_new_post }}</span>
			</div>
		@endif
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<h2><img src="{{ url('/') }}/logo.png" alt="Társadalmi Jóllét"></h2>
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
							<li><a href="{{ url('/') }}">Nyitó</a></li>
							<li class="width-wide"><a href="{{ url('/tortenesek') }}">Történések</a>
								<ul>
									<li><a href="{{ url('tortenesek') }}">Elmúlt 1 hónapban</a></li>
									<li><a href="{{ url('esemenyek') }}">Események</a></li>
								@guest
									<li><a href="{{ url('hirlevelek') }}">Portál Hírlevél</a></li>
								@endif
									<li><a href="{{ url('csoport') }}/hirek">Csoport hírek</a></li>
									<li><a href="{{ url('kezdemenyezes') }}/hirek">Kezdeményezés hírek</a></li>
								</ul>
							<li>
							<li class="width-narrow"><a href="{{ url('tortenesek') }}">Történések</a></li>
							<li><a href="{{ url('/tarsak') }}">Társak</a></li>
							<li><a href="{{ url('/csoportok') }}">Csoportok</a></li>
							<li><a href="{{ url('terkep/tarsak') }}">Térkép</a></li>
							<li class="width-wide"><a href="{{ url('/szellemiseg') }}">Szellemiség</a>
								<ul>
									<li><a href="{{ url('az-uj-vilag-hangjai') }}">Az új világ hangjai</a></li>
									<li><a href="{{ url('irasok') }}">Írások</a></li>
									<li><a href="{{ url('ajanlo') }}">Ajánló</a></li>
								</ul>
							<li>
							<li class="width-wide"><a href="{{ url('esemenyek') }}">Események</a></li>
							<li class="width-narrow"><a href="{{ url('esemenyek') }}">Események</a></li>
							<li><a href="{{ url('kezdemenyezesek') }}">Kezdeményezések</a></li>
							<li class="width-narrow"><a href="{{ url('az-uj-vilag-hangjai') }}">Az új világ hangjai</a></li>
							<li class="width-narrow"><a href="{{ url('irasok') }}">Írások</a></li>
							<li class="width-narrow"><a href="{{ url('hirlevelek') }}">Hírlevél</a></li>
							<li class="width-narrow"><a href="{{ url('csoport') }}/hirek">Csoport hírek</a></li>
							<li class="width-narrow"><a href="{{ url('kezdemenyezes') }}/hirek">Kezdeményezés hírek</a></li>
							<li class="width-narrow"><a href="{{ url('ajanlo') }}">Ajánló</a></li>

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
			<div class="modal fade notice-modal" id="notice-user-modal" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Az elmúlt 2 hét történései a portál tagjainál</h4>
						</div>
						<div class="modal-body" id="notice-user-content"> ... hamarosan megjelenik</div>
						<div class="modal-footer"><input type="hidden" name="notice-user-loaded" id="notice-user-loaded" value="0"></div>
					</div>

				</div>
			</div>
			<div class="modal fade notice-modal" id="notice-group-modal" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Az elmúlt 2 hét történései a csoportodban</h4>
						</div>
						<div class="modal-body" id="notice-group-content"> ... hamarosan megjelenik</div>
						<div class="modal-footer"><input type="hidden" name="notice-group-loaded" id="notice-group-loaded" value="0"></div>
					</div>

				</div>
			</div>
			@endif

			@yield('content')


	    </div>
		<footer class="footer">
			<div>
				<a href="{{ url('/kozossegimegallapodas') }}">Közösségi megállapodás</a> -
				<a href="{{ url('/adatkezeles') }}">Adatkezelési tájékoztató</a> -
				<a href="{{ url('/tudnivalok') }}">Tudnivalók</a> -
				<a href="{{ url('/kapcsolat') }}">Kapcsolat</a>
			</div>
			<div class="right">© 2023 Társadalmi Jóllét Portál</div>
		</footer>
	</div>



    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

	@if( Auth::check())
	<script>
		$(document).on('click','#notice-user',function(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			if($("#notice-user-loaded").val()==0) {

				$.ajax({
					type: "POST",
					url: '{{ url('getUserNotices') }}',
					data: {
						_token: CSRF_TOKEN,
					},
					success: function(data) {
						if(data['status']=='success') {
							$("#notice-user-content").html(data.content_html);
							$("#notice-user-loaded").val(1);
							$("#user_new_post").hide();
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		});


		$(document).on('click','#notice-group',function(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			if($("#notice-group-loaded").val()==0) {

				$.ajax({
					type: "POST",
					url: '{{ url('getGroupNotices') }}',
					data: {
						_token: CSRF_TOKEN,
					},
					success: function(data) {
						if(data['status']=='success') {
							$("#notice-group-content").html(data.content_html);
							$("#notice-group-loaded").val(1);
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		});

	</script>
	@endif


    @yield('footer')

</body>
</html>
