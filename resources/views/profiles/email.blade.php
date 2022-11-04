Kedves {{$name}},
<br><br>
<a href="https://tarsadalmijollet.hu/profil/{{ $sender_id }}">{{$sender_name}}</a> a következőket írta neked:<br>
<p style="border-radius: 5px; padding: 8px; background-color: #fcfdea; text-decoration: none; line-height: 1.6; display: inline-block;">{!! nl2br($sender_message) !!}</p>
<br>
<a href="https://tarsadalmijollet.hu/profil/{{ $sender_id }}" style="padding: 8px 12px; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Válaszolok</a>