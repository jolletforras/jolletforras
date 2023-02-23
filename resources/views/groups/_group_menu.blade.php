	<div class="row group-head">
		<div class="col-sm-12">
			<h2>{!! $group->name !!}</h2>
			<div class="menu">
				<a class="@if ($page=="description") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}">Bemutatkozás</a>
				@if($group->isMember())
				<a class="@if ($page=="conversation") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/beszelgetesek">Beszélgetések</a>
				<a class="@if ($page=="event") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/esemenyek">Események</a>
				<a class="@if ($page=="news") current @endif" href="{{ url('csoport',$group->id) }}/{{$group->slug}}/hirek">Hírek</a>
				@endif
			</div>
		</div>
	</div>