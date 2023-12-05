    <table class="table">
        <tbody>
        @foreach ($guides as $guide)
            <tr>
                <td>
                    <h3><a href="{{ url('tudnivalo',$guide->id) }}/{{$guide->slug}}">{{ $guide->title }}</a></h3>
                    @if (Auth::check() && Auth::user()->admin)
                        <a href="{{url('tudnivalo')}}/{{$guide->id}}/{{$guide->slug}}/modosit">módosít</a>
                    @endif
                    <article>
                        <div class="body">{!! $guide->short_description !!}</div>
                    </article>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
