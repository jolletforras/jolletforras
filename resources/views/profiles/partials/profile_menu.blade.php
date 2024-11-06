			<?php
				$myProfile = $user->myProfile();
				$articlesTab = isset($tab) && $tab=="articles";
				$creationsTab = isset($tab) && $tab=="creations";
			?>
			<div class="narrow-page">
				<div class="row">
					<div class="col-sm-6 menu">
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
							<a href="#category_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/csoportok" type="submit" class="btn btn-default">Írás csoportok</a>
						@endif
						@if($myProfile && $creationsTab)
							<a href="#category_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('alkotas')}}/csoportok" type="submit" class="btn btn-default">Alkotás csoportok</a>
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

					<div class="row">
						<div class="col-sm-12">
							<div class="inner_box collapse" id="category_info" style="font-size: 18px">
								Írásaidat, alkotásaidat csoportokba sorolhatod. A gombra kattintva megnézheted milyen csoportok vannak. Azokat módosíthatod, újat fehetsz fel.
							</div>
						</div>
					</div>
				</div>
			</div>
