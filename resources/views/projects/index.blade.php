@extends('layouts.app')
@section('description', 'Kezdeményezések a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Ismerd meg a Portál tagjainak kezdeményezéseit, csatlakozz hozzánk, vedjél részt bennük, hozd létre a sajátod. Várunk!')
@section('url', 'https://tarsadalmijollet.hu/kezdemenyezesek')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/kezdemenyezesek" /> @endsection
@section('image'){{ url('/images/kezdemenyezesek.jpeg')}}@endsection

@section('content')
	<div class="row">
		<div class="col-sm-5">
			<h2>Kezdeményezések @if(isset($group))- {{$group->name}}@endif</h2>
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
			@if(Auth::check())
			<a href="{{url('kezdemenyezes')}}/uj" type="submit" class="btn btn-default">Új kezdeményezés</a>
			@endif
		</div>
	</div>
	@guest
	<div class="inner_box">
		Itt a Portál tagjainak saját kezdeményezéseit találod. A kezdeményezésnek lehet:<br>
		<ul>
			<li> egy ötletet valaki vagy valakik indulásra késznek gondolnak, akár már el is kezdték a megvalósítását vagy előkészítését, ám még társakat várnak ahhoz, hogy a kezdeményezés meg is valósuljon, működjön vagy fenntarthatóan fejlődhessen</li>
			<li>olyan program, cselekvés, ami már működik és a kezdeményezés tagjai szeretnék ezt megmutatni a Portálon</li>
		</ul>

		Arról, hogyan hozhatsz létre új kezdeményezést és hogyan tudsz kezdeményezésekhez kapcsolódni, <a href="{{url('tudnivalo')}}/12/kezdemenyezesek">ITT</a> találsz részletes tudnivalókat.<br>
		<br>
		Szeretnél értesülni új kezdeményezésekről?<br>
		- Ha van kedved, regisztrálj a Portálon és lépj be ebbe a térbe. Kezdd <a href="{{url('register')}}">ITT</a>.<br>
		- Ha még csak távolabbról ismerkednél, iratkozz fel a hírlevelünkre <a href="https://forms.gle/S18g4L3TAPC9ZMe99">ITT</a>.<br>
	</div>
	@endguest
	<hr style="margin-top:6px;">
	<div class="row">
	<?php
		$i=1;
		$logged_in = Auth::check();
	?>
	@foreach ($projects as $project)
		<?php
			$project_admin = $project->isAdmin();
			$portal_admin = $logged_in && Auth::user()->admin;
			$show = $project->isActive() && $project->approved || $project_admin || $portal_admin;
		?>
		@if($show)
		<div class="col-12 col-sm-6 col-md-4 group">
			<div class="card">
				<div class="card-header"></div>
				<div class="card-title">
					<h3>
						<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">{{ $project->title }}</a>
						@if($project->city!='')
							- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
						@endif
					</h3>
					<p>
						@if($project_admin || $portal_admin)
							<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit">módosít</a>
							@if(!$project->isActive()) <i>/inaktív/</i>@endif
							@if(!$project->approved) <i>/engedélyezésre vár/</i>@endif
						@endif
					</p>
				</div>
				<div class="image-box">
					@if(file_exists(public_path('images/projects/'.$project->id.'.jpg')))
						<div class="image" style="background-image:url('{{url('images')}}/projects/{{$project->id}}.jpg?{{$project->photo_counter}}');"></div>
					@else
						<div class="image" style="background-image:url('{{url('images')}}/tarsadalmijollet.png');"></div>
					@endif
				</div>
				<div class="card-body">
				@if(isset($project->user->id))
					<div>
						@if(strlen($project->body)>800)
							{!! nl2br(mb_substr($project->body,0,800)) !!}
							<a href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">... tovább</a>
						@else
							{!! nl2br($project->body) !!}
						@endif
					</div>
					@if (Auth::check())
						@include('projects._tags')
					@endif
				@endif
				</div>
			</div>
		</div>
		@endif
		@if($i%3==0)
	</div>
	<div class="row">
		@endif
		<?php if($show) $i++ ?>
	@endforeach
	</div>
@endsection

@section('footer')
	@include('partials.search_tag_script',['url'=>'kezdemenyezes'])
@endsection