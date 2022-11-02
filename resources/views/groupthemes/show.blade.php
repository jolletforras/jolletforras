@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $forum->title }}</h2>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/beszelgetesek"> << {{$group->name}}</a>
		</div>
        <div class="panel-body">
			<div class="form-group">
				<b><a href="{{url('profil')}}/{{$forum->user->id}}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>, {{ $forum->updated_at }}</b>
				@if (Auth::user()->id==$forum->user->id)
					<a href="{{url('forum')}}/{{$forum->id}}/{{$forum->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
				@endif
			</div>
			<div class="form-group">
				{!! $forum->body !!}
			</div>
			<div class="form-group">
				@include('forums.tags')
			</div>
			<div class="form-group">
				<input type="checkbox" onchange="check()" name="comment_notice" id="comment_notice" value="hozzászólás értesítés"  @if ($askNotice) checked @endif>
				Kérek értesítést új hozzászólás esetén
			</div>
	    </div>
    </div>

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
		<button type="button" onclick="save()">Mentés</button>
	</div>
	@include('partials.comment_script', [
        'commentable_type'	=>'GroupTheme',
        'commentable_url'	=>'csoport/'.$group->id.'/'.$group->slug.'/tema/'.$forum->id.'/'.$forum->slug,
        'commentable_id'	=>$forum->id,
        'name'				=>$forum->user->name,
        'email'				=>$forum->user->email
    ] )
	<script type="text/javascript">
		function check(){
			var ask_notice = 0;
			if($("#comment_notice").is(':checked')) ask_notice=1;

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{ url('ask_comment_notice') }}',
				data: {
					_token: CSRF_TOKEN,
					forum_id: "{{$forum->id}}",
					ask_notice: ask_notice
				},
				success: function(data) {
					if(data['status']=='success') {}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}
	</script>
@endsection

