<table class="table">
    <tbody>
    @foreach ($newsletters as $newsletter)
        <tr>
            <td>
                <h3><a href="{{ url('hirlevel',$newsletter->id) }}/{{$newsletter->slug}}">{{ $newsletter->title }}</a></h3>
                @if (Auth::check() && Auth::user()->admin)
                    <a href="{{url('hirlevel')}}/{{$newsletter->id}}/{{$newsletter->slug}}/modosit">módosít</a>
                @endif
                <article>
                    <div class="body">{!! $newsletter->short_description !!}  <a href="{{ url('hirlevel',$newsletter->id) }}/{{$newsletter->slug}}">... tovább</a></div>
                </article>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>