			<?php
				$myProfile = $user->myProfile();
				$articlesTab = isset($tab) && $tab=="articles";
				$creationsTab = isset($tab) && $tab=="creations";
			?>
			<div class="narrow-page">
				<div class="row">
					<div class="col-sm-9 menu">
						<h2>{!! $user->name !!}</h2>
						<a class="@if (empty($tab) || $tab=="introduction") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}">Bemutatkozás</a>
						@if($myProfile || $user->has_article)
							<a class="@if ($articlesTab) current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}/irasok">Írások</a>
						@endif
						@if($myProfile || $user->has_creation)
							<a class="@if ($creationsTab) current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}/alkotasok">Alkotások</a>
						@endif
					</div>
					<div class="col-sm-3 text-right">
						@if($myProfile && $articlesTab)
						<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új írás</a>
						@endif
						@if($myProfile && $creationsTab)
							<a href="#creation_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('alkotas')}}/uj" type="submit" class="btn btn-default">Új alkotás</a>
						@endif
					</div>
				</div>
			</div>
