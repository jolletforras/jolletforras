<div class="narrow-page">
    <div class="form-group">
    @if(!$comments->isEmpty())<b>Hozzászólások:</b><br/>@endif
        <hr>
        <div class="comments">
        @foreach ($comments as $comment)
            <div  id="comment-{{$comment->id}}" @if(isset($comment->to_user_id))class="comment level2" @else class="comment"@endif>
            <?php $to_user = isset($comment->to_user_id) ? '<a href="'.url('profil').'/'.$comment->to_user->id.'/'.$comment->to_user->slug.'">'.$comment->to_user->name.'</a>, ':'';  ?>
                <a name="{{$comment->id}}"></a>
                <span style='font-size:14px;'><a href="{{url('profil')}}/{{$comment->commenter->id}}/{{$comment->commenter->slug}}">{{ $comment->commenter->name }}</a></span> <br/>
            @if(isset($comment->shorted_text))
                <div id="shorted-{{$comment->id}}" >{!! $to_user !!}}{!! nl2br($comment->shorted_text) !!} <a class="more" data-value="{{$comment->id}}">... tovább</a> </div>
            @endif
                <div id="full-{{$comment->id}}" @if(isset($comment->shorted_text))style="display: none;"@endif>{!! $to_user !!}{!! nl2br($comment->body) !!}</div>
            </div>
            <div class="answer" @if(isset($comment->to_user_id))style="margin-left: 40px;"@else style="margin-left: 10px;"@endif><a href="#valasz" onclick="answer({{$comment->id}},{{$comment->commenter->id}})">Válasz</a> <span style="margin-left:20px;">{{ $comment->since }}</span></div>
        @endforeach
        </div>
    </div>
    <hr>
    <a name="valasz"></a>
    <div id="comment-for-answer" style="background-color:#fefffd; border-radius:10px; padding:10px; display: none; margin-bottom: 15px;"></div>
    <div class="form-group">
        <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Ide írva szólhatsz hozzá"></textarea>
    </div>
    <div class="form-group ">
        <input type="hidden" name="to_comment_id" id="to_comment_id" value="">
        <input type="hidden" name="to_user_id" id="to_user_id" value="">
        <button type="button" onclick="save()">Küldés</button>
        <button type="button" id="cancel" style="display: none;" onclick="cancel()">Mégse válaszolok</button>
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