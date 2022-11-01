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
                        @if($user->city=="Budapest")
                            @if($user->location!='')
                                {{ $user->location }}@if(!is_numeric(stripos($user->location,"Budapest"))), Budapest @endif
                            @else
                                Budapest
                            @endif
                        @else
                            @if($user->location!='' && $user->location!=$user->city){{ $user->location }}, @endif
                            {{ $user->city }}
                        @endif
                        <br>
                        @if($type=='tab1')
                            @if(strlen($user->introduction)>721)
                                {!! nl2br(mb_substr($user->introduction,0,721)) !!}
                                <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">... tovább</a>
                            @else
                                {!! nl2br($user->introduction) !!}
                            @endif
                            <p>jártasság, tudás: @include('profiles.partials.tags')</p>
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