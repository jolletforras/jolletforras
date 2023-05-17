			<div class="menu narrow-page">
				<h2>{!! $user->name !!}</h2>
				<a class="@if ($tab=="introduction") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}">Bemutatkozás</a>
				@if($user->has_article)
				<a class="@if ($tab=="articles") current @endif" href="{{ url('profil',$user->id) }}/{{$user->slug}}/irasok">Írások</a>
				@endif
			</div>
