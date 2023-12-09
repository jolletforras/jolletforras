@extends('layouts.app')

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $forum->title }}</h2>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/beszelgetesek"> << {{$group->name}}</a>
		</div>
        <div class="panel-body">
			<div class="form-group">
				<span class="author"><b><a href="{{url('profil')}}/{{$forum->user->id}}/{{$forum->user->slug}}">{{ $forum->user->name }}</a>, {{ $forum->created_at }}</b></span>
				@if (Auth::user()->id==$forum->user->id || $forum->announcement && $group->isAdmin())
					<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/tema/{{$forum->id}}/{{$forum->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
				@endif
			</div>
			<div class="form-group">
				{!! $forum->body !!}
			</div>
			<div class="form-group">
				@include('partials.tags',['url'=>'csoport/'.$group->id.'/'.$group->slug.'/tema','obj'=>$forum])
			</div>
			<div class="form-group">
				<input type="checkbox" onchange="check()" name="comment_notice" id="comment_notice" value="hozzászólás értesítés"  @if ($askNotice) checked @endif>
				Kérek értesítést új hozzászólás esetén
			</div>
			@if ($users_read_it)
			<div class="form-group">
				Látta: <a href="#seen" data-toggle="collapse">{{$num_user_read_it}} ember</a>
				<div class="row">
					<div class="col-sm-12"><div class="collapse" id="seen">{!! $users_read_it !!}</div></div>
				</div>
			</div>
			@endif
	    </div>
    </div>

	@include('comments._show', ['comments' => $comments, 'can_comment'=>$group->isActive()] )
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
			'commentable_type'	=>'GroupTheme',
			'commentable_url'	=>'csoport/'.$group->id.'/'.$group->slug.'/tema/'.$forum->id.'/'.$forum->slug,
            'commentable_id'	=>$forum->id,
            'name'				=>$forum->user->name,
            'email'				=>$forum->user->email
        ] )
	@endif

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
