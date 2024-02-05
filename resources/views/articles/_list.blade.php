@foreach ($articles as $article)
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header"></div>
            <div class="image-box">
                <div class="image" style="background-image:url('/images/posts/{{$article->image}}');"></div>
            </div>
            <div class="card-body">
                <h3>
                    <a href="{{ url('iras',$article->id) }}/{{$article->slug}}">{{ $article->title }}</a>
                    @if (Auth::check() && (Auth::user()->id==$article->user->id || Auth::user()->admin))
                        <a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" class="edit">módosít</a><br>
                    @endif
                </h3>
                <div>{!! $article->short_description !!}{!! $article->short_description !!}</div>
            </div>
        </div>
    </div>
@endforeach