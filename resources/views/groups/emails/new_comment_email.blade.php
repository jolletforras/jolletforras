Kedves {{$name}},
<br><br>
A(z) <b>{{ $group_name }}</b> csoportodban <b><a href="https://tarsadalmijollet.hu/{{ $post_url }}"> {{$post_title}}</a></b> {{$type_txt2}} új hozzászólás történt.<br>
<a href="https://tarsadalmijollet.hu/{{ $post_url }}" style="margin-top:10px; padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Elolvasom</a>
<br><br>
<a href="https://tarsadalmijollet.hu/profil/{{ $user_id }}">{{$author_name}}</a> a következőket írta a hozzászólásában:<br>
<p style="border-radius: 5px; padding: 8px; background-color: #fcfdea; text-decoration: none; line-height: 1.6; display: inline-block;">{!! nl2br($comment) !!}</p>
<br>
<a href="https://tarsadalmijollet.hu/{{ $post_url }}" style="margin-top:10px; padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Válaszolok</a>
<br><br>
@if($can_login_with_code)
<b>Egy napig</b> a levélből megnyithatod a bejegyzést, akkor is, ha előzőleg nem voltál bejelentkezve. Ezért fontos, hogy 1 napig <b>ne továbbítsd</b> a leveledet mások számára, mert akkor ők is ugyanúgy be tudnak lépni a nevedben.
Ha nem szeretnéd, hogy a levélből a bejegyzés előzetes bejelentkezés nélkül is megnyitható legyen, kattints <a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és vedd ki ennek engedélyezésénél a pipát.
<b>Egy nap után</b> a teljes bejegyzés elolvasásához vagy válaszoláshoz előzetes bejelentkezés szükséges.<br>
@else
A teljes bejegyzés elolvasásához vagy válaszoláshoz előzetes bejelentkezés szükséges.<br>
@endif
A válaszoláshoz a <b>"Válaszolok" gombra</b> kattints! Ha a tarsadalmi.jollet@gmail.com címre válaszolsz, azt a többiek nem fogják látni.<br>
Ha nem szeretnél a jövőben értesítéseket kapni, kattints <a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és vedd ki az értesítésnél a pipát.