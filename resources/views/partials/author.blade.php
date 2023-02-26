<p>
    <i style="font-size: 12px; color: #494949; font-family: Arial;">{{$author}}@if(Auth::check() || $obj->user->public)<a href="{{ url('profil',$obj->user->id) }}/{{$obj->user->slug}}">{{ $obj->user->name }}</a>@else{{ $obj->user->name }}@endif, {{ $obj->created_at }}@if( $obj->updated_at!=$obj->created_at), módosítva:  {{ $obj->updated_at }}@endif
    </i>
</p>