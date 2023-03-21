<table class="table">
    <tbody>
    @foreach ($newsletters as $newsletter)
        <tr>
            <td>
                <h3><a href="{{ url('hirlevel',$newsletter->id) }}/{{$newsletter->slug}}">{{ $newsletter->title }}</a></h3>
                <a href="{{ url('profil',$newsletter->user->id) }}/{{$newsletter->user->slug}}">{{ $newsletter->user->name }}</a>,	{{ $newsletter->updated_at }}
                @if (Auth::check() && Auth::user()->admin)
                    <a href="{{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}/modosit">módosít</a>
                @endif
                <article>
                    <div class="body">
                        {!! justbr($newsletter->body,1000) !!} <a href="{{ url('hirlevel',$newsletter->id) }}/{{$newsletter->slug}}">... tovább</a>
                    </div>
                </article>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>