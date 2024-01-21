<div class="narrow-page">
    @if(!$comments->isEmpty())
    <hr>
    <div class="form-group">
        <div class="comments">
            <p><b>Hozzászólások:</b></p>
        @foreach ($comments as $comment)
                <?php
                $lev1_comment_id = empty($comment->to_user_id) ? $comment->id : $lev1_comment_id;
                $level2_comment = empty($comment->to_user_id) ? false : true;
                $class_comment = $level2_comment ? "comment level2" : "comment";
                $to_user = $level2_comment ? '<a href="'.url('profil').'/'.$comment->to_user->id.'/'.$comment->to_user->slug.'">'.$comment->to_user->name.'</a>, ':'';
                $space_left = $level2_comment ? 40 : 10;
                $display_full_comment = isset($comment->shorted_text) ? ' style="display: none;"':' style="display: inline-block;"';
                ?>
            <div  id="comment-{{$comment->id}}" class="{{$class_comment}}">
                <a name="{{$comment->id}}"></a>
                <span style='font-size:14px;'>
                    <a href="{{url('profil')}}/{{$comment->commenter->id}}/{{$comment->commenter->slug}}">{{ $comment->commenter->name }}</a>
                    @if (Auth::user()->id==$comment->commenter->id && $comment->updated_at>date('Y-m-d H:i', strtotime('-1 hour')))
                        <a href="#hozzaszol" onclick="edit({{$comment->id}})" style="margin-left: 20px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    @endif
                </span>
                <br/>
                {!! $to_user !!}
            @if(isset($comment->shorted_text))
                <div id="shorted-{{$comment->id}}" style="display: inline-block;">{!! nl2br($comment->shorted_text) !!} <a class="more" data-value="{{$comment->id}}">... tovább</a> </div>
            @endif
                <div id="full-{{$comment->id}}"{!! $display_full_comment !!}>{!! nl2br($comment->body) !!}</div>
            </div>
            <div class="answer" style="margin-left: {{$space_left}}px;">
                @if(!isset($can_comment) || $can_comment)
                <a style="margin-right:20px;" href="#hozzaszol" onclick="answer({{$lev1_comment_id}},{{$comment->id}},{{$comment->commenter->id}})">Válasz</a>
                @endif
                <span>{{ $comment->since }}</span>
            </div>
        @endforeach
        </div>
    </div>
    @endif
    @if(!isset($can_comment) || $can_comment)
    <hr>
    <a name="hozzaszol"></a>
    <div id="comment-for-answer" style="background-color:#fefffd; border-radius:10px; padding:10px; display: none; margin-bottom: 15px;"></div>
    <div class="form-group">
        <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Ide írva szólhatsz hozzá" onclick="$('#comment').focus()"></textarea>
    </div>
    <div class="form-group ">
        <input type="hidden" name="update_comment_id" id="update_comment_id" value="">
        <input type="hidden" name="lev1_comment_id" id="lev1_comment_id" value="">
        <input type="hidden" name="to_comment_id" id="to_comment_id" value="">
        <input type="hidden" name="to_user_id" id="to_user_id" value="">
        <button type="button" onclick="save()" id="add_comment_btn">Küldés</button>
        <button type="button" onclick="update()" id="update_comment_btn" style="display: none;">Módosít</button>
        <button type="button" id="cancel" style="display: none;" onclick="cancel()">Mégse válaszolok</button>
    </div>
    @endif
</div>
