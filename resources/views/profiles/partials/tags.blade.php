@unless($user->tags->isEmpty())
    @if (Auth::check())
        @for ($i = 0; $i < $last=count($user->tags)-1; $i++)
        <a href="{{ url('tagok')}}/ertes/{{$user->tags[$i]->id }}/{{$user->tags[$i]->slug}}">{{ $user->tags[$i]->name }}</a>,
        @endfor
        <a href="{{ url('tagok')}}/ertes/{{$user->tags[$last]->id }}/{{$user->tags[$last]->slug}}">{{ $user->tags[$last]->name }}</a>
    @else
        @for ($i = 0; $i < $last=count($user->tags)-1; $i++)
        {{ $user->tags[$i]->name }},
        @endfor
        {{ $user->tags[$last]->name }}
    @endif
@endunless