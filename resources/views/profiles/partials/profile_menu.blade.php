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
							<a href="#category_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras-temakor')}}/uj" type="submit" class="btn btn-default">Témakör felvétele</a>
						@endif
						@if(false && $myProfile && $creationsTab)
							<a href="#category_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('alkotas-temakor')}}/uj" type="submit" class="btn btn-default">Témakör felvétele</a>
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

					@if($myProfile && ($articlesTab || $creationsTab))
					<div class="row">
						<div class="col-sm-12">
							<div class="inner_box collapse" id="category_info" style="font-size: 18px">
								@if($articlesTab)
								Ha szeretnéd, akkor írásodat valamelyik korábban létrehozott témakörbe sorolhatod. Amennyiben még nincs létrehozva témakör, az írásod elmentése után létrehozhatod, majd az írásod módosításánál már beállíthatod a felvett új témakört.
								@endif
								@if($creationsTab)
								Ha szeretnéd, akkor alkotásodat valamelyik korábban létrehozott témakörbe sorolhatod. Amennyiben még nincs létrehozva témakör, az alkotásod elmentése után létrehozhatod, majd az alkotásod módosításánál már beállíthatod a felvett új témakört.
								@endif
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
