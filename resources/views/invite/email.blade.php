Üdvözlünk,<br>
<br>
{{$user_name}} ({{$user_email}}) meghívott a <a href="{{url('/')}}"> jolletforras.hu</a> oldalra. Amennyiben válaszolni szeretnél neki, válaszod a {{$user_email}} email címre küldd!
<br><br>
@if(!empty($invite_msg))
    Ezt írta neked:<br>
    <br>
    "{{$invite_msg}}"
@endif

