Kedves {{$name}},
<br><br>
<a href="https://tarsadalmijollet.hu/{{ $sender_url }}">{{$sender_name}}</a> a következőket írta neked:<br>
<p style="border-radius: 5px; padding: 8px; background-color: #fcfdea; text-decoration: none; line-height: 1.6; display: inline-block;">{!! nl2br($sender_message) !!}</p>
<br>
A válaszoláshoz a <b>Válaszolok</b> gombra kattints (ha a tarsadalmi.jollet@gmail.com címre írsz, a válaszod nem fogja látni).<br>
<br>
<a href="https://tarsadalmijollet.hu/{{ $sender_url }}" style="padding: 8px 12px; background-color: #5cb85c; border: 1px solid #4cae4c; border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff; text-decoration: none;font-weight:bold;display: inline-block;">Válaszolok</a><br>
<br>
@if($can_login_with_code)
    <b>Egy napig</b> a levélből megnyithatod a bejegyzést, akkor is, ha előzőleg nem voltál bejelentkezve. Ezért fontos, hogy 1 napig <b>ne továbbítsd</b> a leveledet mások számára, mert akkor ők is ugyanúgy be tudnak lépni a nevedben.
    Ha nem szeretnéd, hogy a levélből a bejegyzés előzetes bejelentkezés nélkül is megnyitható legyen, kattints <a href="https://tarsadalmijollet.hu/profilom/beallitasok">IDE</a> és vedd ki ennek engedélyezésénél a pipát.
    <b>Egy nap után</b> a teljes bejegyzés elolvasásához vagy válaszoláshoz előzetes bejelentkezés szükséges.<br>
@else
    A válaszoláshoz bejelentkezés szükséges és a bemutatkozása alatt tudsz írni neki.
@endif

