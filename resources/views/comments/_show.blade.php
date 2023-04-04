<div class="narrow-page">
    <div class="form-group">
        @if(!$comments->isEmpty())<b>Hozzászólások:</b><br/>@endif
        <hr>
        <div class="comments">
            @foreach ($comments as $comment)
                <a name="{{$comment->id}}"></a>
                <b><a href="{{url('profil')}}/{{$comment->commenter->id}}/{{$comment->commenter->slug}}">{{ $comment->commenter->name }}</a></b>, <b>{{ $comment->created_at }}</b> <br/>
                @if(isset($comment->shorted_text))
                <div id="shorted-{{$comment->id}}">{!! nl2br($comment->shorted_text) !!} <a class="more" data-value="{{$comment->id}}">... tovább</a> </div>
                @endif
                <div id="full-{{$comment->id}}" @if(isset($comment->shorted_text)) style="display: none;"@endif>{!! nl2br($comment->body) !!}</div>
                <hr>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Ide írva szólhatsz hozzá"></textarea>
    </div>
    <div class="form-group ">
        <button type="button" onclick="save()">Küldés</button>
    </div>
</div>
@section('footer')
    @include('partials.comment_script', [
        'commentable_type'	=>$commentable_type,
        'commentable_url'	=>$commentable_url,
        'commentable_id'	=>$commentable->id,
        'name'				=>$commentable->user->name,
        'email'				=>$commentable->user->email
    ] )
@endsection