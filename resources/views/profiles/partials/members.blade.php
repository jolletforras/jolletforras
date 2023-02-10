<div class="panel panel-default">
    <div class="panel-body"  id="result">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="60px" class="img_column">Kép</th>
                <th>Bemutatkozás</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="img_column">
                        @if(file_exists(public_path('images/profiles/k_'.$user->id.'.jpg')))
                            <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">
                                <img src="{{ url('/images/profiles') }}/k_{{ $user->id}}.jpg">
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
                        @if($type=='tab1')
                            {!! justbr($user->introduction,721) !!}
                            @if(strlen(strip_tags($user->introduction))>721)
                                <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">... tovább</a>
                            @endif
                            <p>jártasság, tudás: @include('profiles.partials.skill_tags')
                            @unless($user->interest_tags->isEmpty())<br>érdeklődés, tanulás: @include('profiles.partials.interest_tags')@endunless</p>
                        @elseif($type=='tab2')
                            {{ $user->interest }}
                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>