Kedves {{$name}},
<br><br>
A(z) <b>{{ $group_name }}</b> csoportodban <b><a href="https://tarsadalmijollet.hu/{{ $theme_url }}"> {{$theme_title}}</a></b> beszélgetésnél új hozzászólás történt.<br>
Elolvasáshoz kérlek  kattints <a href="https://tarsadalmijollet.hu/{{ $theme_url }}"> IDE</a>.
<br><br>
<a href="https://tarsadalmijollet.hu/profil/{{ $user_id }}">{{$author_name}}</a> a következőket írta a hozzászólásában:<br>
<div>"{!! nl2br($comment) !!}"</div>
<br>
Ha nem szeretnél a jövőben értesítéseket kapni, kattints
<a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és szedd ki az értesítésnél a pipát.