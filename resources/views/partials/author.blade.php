<p>
    <i>{{$author}}@if(Auth::check() || $obj->user->public)<a href="{{ url('profil',$obj->user->id) }}/{{$obj->user->slug}}">{{ $obj->user->name }}</a>@else{{ $obj->user->name }}@endif, létrehozva: {{ $obj->created_at }}@if( $obj->updated_at!=$obj->created_at), módosítva:  {{ $obj->updated_at }}@endif
    </i>
</p>