Kedves {{$name}},
<br><br>
A(z) <b>{{ $group_name }}</b> csoportodban <b><a href="https://tarsadalmijollet.hu/{{ $theme_url }}"> {{$theme_title}}</a></b> beszélgetésnél új hozzászólás történt.<br>
<a href="https://tarsadalmijollet.hu/{{ $theme_url }}" style="padding: 8px 12px; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Elolvasom</a>
<br><br>
<a href="https://tarsadalmijollet.hu/profil/{{ $user_id }}">{{$author_name}}</a> a következőket írta a hozzászólásában:<br>
<p style="border-radius: 5px; padding: 8px; background-color: #fcfdea; text-decoration: none; line-height: 1.6; display: inline-block;">{!! nl2br($comment) !!}</p>
<br>
Ha nem szeretnél a jövőben értesítéseket kapni, kattints
<a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és szedd ki az értesítésnél a pipát.