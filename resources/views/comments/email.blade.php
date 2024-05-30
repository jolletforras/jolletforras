Kedves {{$name}},
<br><br>
Hozzászóltak a {{$commentable_type}} bejegyzésedhez.<br>
<a href="{{url('/')}}/{{$commentable_url}}" style="margin-top:10px; padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Elolvasom</a>
<br><br>
<a href="https://jolletforras.hu/profil/{{ $commenter_id }}">{{$commenter_name}}</a> a következőket írta:<br>
<p style="border-radius: 5px; padding: 8px; background-color: #fcfdea; text-decoration: none; line-height: 1.6; display: inline-block;">{!! nl2br($comment) !!}</p>
<br>
<a href="{{url('/')}}/{{$commentable_url}}" style="margin-top:10px; padding: 8px 12px; background: #f5fcf5; border: 1px solid #464646;border-radius: 5px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #464646;text-decoration: none;font-weight:bold;display: inline-block;">Válaszolok</a>
<br><br>
A válaszoláshoz bejelentkezés szükséges. A válaszoláshoz a "Válaszolok" gombra kattints, ha a jolletforras@gmail.com címre válaszolsz, azt a többiek nem fogják látni. Ha nem szeretnél a jövőben értesítéseket kapni, kattints <a href="{{url('/profilom')}}/beallitasok">IDE</a> és szedd ki az értesítésnél a pipát.