Kedves {{$name}},
<br><br>
Hozzászóltak a {{$commentable_type}} bejegyzésedhez.<br>
A hozzászólás felkereséséhez kérlek  kattints <a href="{{url('/')}}/{{$commentable_url}}"> IDE</a>.
<br><br>
{{$commenter_name}} nevű felhasználó a következőt írta:<br>
"{!! nl2br($comment) !!}"<br>
<br>
Ha nem szeretnél a jövőben értesítéseket kapni, kattints
<a href="{{url('/profilom')}}/beallitasok">IDE</a> és szedd ki az értesítésnél a pipát.