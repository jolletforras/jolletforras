<div class="form-group">
    @if(!$comments->isEmpty())<b>Hozzászólások:</b><br/>@endif
    <hr>
    <div class="comments">
        @foreach ($comments as $comment)
            <b><a href="{{url('profil')}}/{{$comment->commenter->id}}/{{$comment->commenter->slug}}">{{ $comment->commenter->name }}</a></b>, <b>{{ $comment->updated_at }}</b> <br/>
            {!! nl2br($comment->body) !!}<br/>
            <hr>
        @endforeach
    </div>
</div>
<div class="form-group">
    <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Ide írva szólhatsz hozzá"></textarea>
</div>
<div class="form-group">
    <button type="button" onclick="save()">Küldés</button>
</div>
@include('partials.comment_script', [
    'commentable_type'	=>$commentable_type,
    'commentable_url'	=>$commentable_url,
    'commentable_id'	=>$commentable->id,
    'name'				=>$commentable->user->name,
    'email'				=>$commentable->user->email
] )