<div class="narrow-page">
    <div class="form-group">
    @if(!$comments->isEmpty())<b>Hozzászólások:</b><br/>@endif
        <hr>
        <div class="comments">
        @foreach ($comments as $comment)
                <?php
                $class_comment = isset($comment->to_user_id) ? "comment level2" : "comment";
                $to_user = isset($comment->to_user_id) ? '<a href="'.url('profil').'/'.$comment->to_user->id.'/'.$comment->to_user->slug.'">'.$comment->to_user->name.'</a>, ':'';
                $space_left = isset($comment->to_user_id) ? 40 : 10;
                $display_full_comment = isset($comment->shorted_text) ? ' style="display: none;"':'';
                ?>
            <div  id="comment-{{$comment->id}}" class="{{$class_comment}}">
                <a name="{{$comment->id}}"></a>
                <span style='font-size:14px;'><a href="{{url('profil')}}/{{$comment->commenter->id}}/{{$comment->commenter->slug}}">{{ $comment->commenter->name }}</a></span> <br/>
            @if(isset($comment->shorted_text))
                <div id="shorted-{{$comment->id}}">{!! $to_user !!}}{!! nl2br($comment->shorted_text) !!} <a class="more" data-value="{{$comment->id}}">... tovább</a> </div>
            @endif
                <div id="full-{{$comment->id}}"{{$display_full_comment}}>{!! $to_user !!}{!! nl2br($comment->body) !!}</div>
            </div>
            <div class="answer" style="margin-left: {{$space_left}}px;"><a href="#valasz" onclick="answer({{$comment->id}},{{$comment->commenter->id}})">Válasz</a> <span style="margin-left:20px;">{{ $comment->since }}</span></div>
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
