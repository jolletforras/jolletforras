<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="60px" class="img_column">Kép</th>
                <th>Azért vagyok a csoport tagja, mert ...</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="img_column">
                        @if(file_exists(public_path('images/profiles/k_'.$user->id.'.jpg')))
                            <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">
                                <img src="{{ url('/images/profiles') }}/k_{{ $user->id}}.jpg?{{ $user->photo_counter}}">
                            </a>
                        @elseif (Auth::check() && Auth::user()->id==$user->id)
                            <a href="{{ url('/profilom') }}/feltolt_profilkep">
                                <img src="{{ url('/images/profiles') }}/k_feltolt_nincs_kep.png">
                            </a>
                        @else
                            <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">
                                <img src="{{ url('/images/profiles') }}/k_nincs_kep.jpg">
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">{{ $user->name }}</a>
                        {{ $user->full_location }}
                        <br>
                        {{$user->pivot->motivation}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>