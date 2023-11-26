			<div class="menu narrow-page" style="margin-bottom: 12px;">
				<h2>{!! $user->name !!}</h2>
				<a class="@if (empty($tab) || $tab=="introduction") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}">Bemutatkozás</a>
				@if($user->has_article)
				<a class="@if (isset($tab) && $tab=="articles") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}/irasok">Írások</a>
				@endif
			</div>
