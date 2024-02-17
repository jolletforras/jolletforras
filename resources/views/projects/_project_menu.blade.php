	<div class="row group-head narrow-page">
		<div class="col-sm-12">
			<h2>
				{{ $project->title }}
				@if($project->city!='')
					- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
				@endif
			</h2>
			<div class="menu">
				<?php
					$show_user = Auth::guest() && $project->user_visibility=='public' || Auth::check() && $project->user_visibility!='project' || $project->isMember();
					$show_news = $project->isMember() || $project->hasNews();
				?>

				@if($project->isMember() || $show_user || $show_news)
					<a class="@if ($page=="description") current @endif" href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}">Bemutatás</a>
				@endif

				@if($show_news)
					<a class="@if ($page=="news") current @endif" href="{{ url('kezdemenyezes',$project->id) }}/{{$project->slug}}/hirek">Hírek</a>
				@endif
			</div>
			<hr style="margin-top:10px;margin-bottom:5px;">
		</div>
	</div>