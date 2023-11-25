	<div class="row group-head narrow-page">
		<div class="col-sm-12">
			<h2>{!! $group->name !!}</h2>
			<div class="menu">
				<a class="@if ($page=="description") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}">Bemutatkozás</a>

				@if(Auth::guest() && $group->user_visibility=='public' || Auth::check() && ($group->user_visibility=='portal' || $group->isMember()))
					<a class="@if ($page=="members") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/tagok">Tagok</a>
				@endif

				@if(Auth::check() && $group->isMember())
					<a class="@if ($page=="conversation") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/beszelgetesek">Beszélgetések</a>
					<a class="@if ($page=="event") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/esemenyek">Események</a>
				@endif

				<a class="@if ($page=="news") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/hirek">Hírek</a>

				@if(Auth::check() && $group->isMember())
					<a class="@if ($page=="announcement") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/kozlemenyek">Közlemények</a>
				@endif
			</div>
			<hr style="margin-top:10px;margin-bottom:5px;">
		</div>
	</div>