<div class="panel panel-default">
    <div class="panel-body">
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
                    <?php
                    $html_image = '';
                    if(file_exists(public_path('images/profiles/k_'.$user->id.'.jpg'))) {
                        $html_image = '<img src="'.url('/images/profiles').'/k_'.$user->id.'.jpg?'.$user->photo_counter.'">';
                    }
                    ?>
                    <td class="img_column">
                        <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">
                            {!! $html_image  !!}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('profil',$user->id) }}/{{$user->slug}}"><span class="list_profile_image">{!!$html_image!!}</span>{{ $user->name }}</a>
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