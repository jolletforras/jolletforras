Kedves {{$name}},
<br><br>
A(z) <b>{{ $group_name }}</b> csoportodban <b><a href="https://tarsadalmijollet.hu/{{ $post_url }}"> {{$post_title}}</a></b> néven új {{$type_txt1}} hoztak létre.<br>
<a href="https://tarsadalmijollet.hu/{{ $post_url }}" style="margin-top:10px; padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Elolvasom</a>
<br><br>
<a href="https://tarsadalmijollet.hu/profil/{{ $user_id }}">{{$author_name}}</a> a következőket írta:<br>
<p>{!! $post  !!}</p>
<a href="https://tarsadalmijollet.hu/{{ $post_url }}" style="padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Hozzászólok</a>
<br><br>
@if($can_login_with_code)
<b>Egy napig</b> a levélből megnyithatod a bejegyzést, akkor is, ha előzőleg nem voltál bejelentkezve. Ezért fontos, hogy 1 napig <b>ne továbbítsd</b> a leveledet mások számára, mert akkor ők is ugyanúgy be tudnak lépni a nevedben.
Ha nem szeretnéd, hogy a levélből a bejegyzés előzetes bejelentkezés nélkül is megnyitható legyen, kattints <a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és vedd ki ennek engedélyezésénél a pipát.
<b>Egy nap után</b> a teljes bejegyzés elolvasásához vagy válaszoláshoz előzetes bejelentkezés szükséges.<br>
@else
A teljes bejegyzés elolvasásához vagy hozzászóláshoz bejelentkezés szükséges.<br>
@endif
@if($type=="téma")Ha nem szeretnél a jövőben értesítéseket kapni, kattints <a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és szedd ki az értesítésnél a pipát.@endif