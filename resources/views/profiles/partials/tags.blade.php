@unless($user->skill_tags->isEmpty())
    @if (Auth::check())
        @for ($i = 0; $i < $last=count($user->skill_tags)-1; $i++)
        <a href="{{ url('tagok')}}/ertes/{{$user->skill_tags[$i]->id }}/{{$user->skill_tags[$i]->slug}}">{{ $user->skill_tags[$i]->name }}</a>,
        @endfor
        <a href="{{ url('tagok')}}/ertes/{{$user->skill_tags[$last]->id }}/{{$user->skill_tags[$last]->slug}}">{{ $user->skill_tags[$last]->name }}</a>
    @else
        @for ($i = 0; $i < $last=count($user->skill_tags)-1; $i++)
        {{ $user->skill_tags[$i]->name }},
        @endfor
        {{ $user->skill_tags[$last]->name }}
    @endif
@endunless