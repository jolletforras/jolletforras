			<div class="narrow-page">
				<div class="row">
					<div class="col-sm-9 menu">
						<h2>{!! $user->name !!}</h2>
						<a class="@if (empty($tab) || $tab=="introduction") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}">Bemutatkozás</a>
						@if($user->has_article)
							<a class="@if (isset($tab) && $tab=="articles") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}/irasok">Írások</a>
						@endif
					</div>
					<div class="col-sm-3 text-right">
						@if($user->myProfile() && isset($tab) && $tab=="articles")
						<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új Írás</a>
						@endif
					</div>
				</div>
			</div>
