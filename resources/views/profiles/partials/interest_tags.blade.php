@unless($user->interest_tags->isEmpty())
    @if (Auth::check())
        @for ($i = 0; $i < $last=count($user->interest_tags)-1; $i++)
        <a href="{{ url('tagok')}}/erdeklodes/{{$user->interest_tags[$i]->id }}/{{$user->interest_tags[$i]->slug}}">{{ $user->interest_tags[$i]->name }}</a>,
        @endfor
        <a href="{{ url('tagok')}}/erdeklodes/{{$user->interest_tags[$last]->id }}/{{$user->interest_tags[$last]->slug}}">{{ $user->interest_tags[$last]->name }}</a>
    @else
        @for ($i = 0; $i < $last=count($user->interest_tags)-1; $i++)
        {{ $user->interest_tags[$i]->name }},
        @endfor
        {{ $user->interest_tags[$last]->name }}
    @endif
@endunless