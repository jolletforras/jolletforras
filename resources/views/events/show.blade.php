@extends('layouts.app')
@section('title'){{ $event->title }}@endsection
@section('description'){{ $event->meta_description }}@endsection
@section('url'){{url('esemeny')}}/{{$event->id}}/{{$event->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}" /> @endsection
@section('image')@if(!empty($event->image)){{url('/')}}/images/posts/{{$event->image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>{{ $event->title }}</h2>
			@if($event->isGroupEvent())&nbsp;&nbsp;&nbsp;&nbsp;
				@if( Auth::check())	 <a href="{{url('csoport')}}/{{$event->group->id}}/{{$event->group->slug}}/esemenyek"> << {{$event->group->name}} - események</a>
				@else 	<a href="{{url('csoport')}}/{{$event->group->id}}/{{$event->group->slug}}"> << {{$event->group->name}}</a>@endif
			@endif
		</div>
		<div class="panel-body">
			@if ($has_access)
				<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			{!! $event->body !!}
			@include('partials.author', ['author'=>'Eseményt felvette: ','obj'=>$event])
			@if($event->isGroupEvent() && Auth::check() && $event->group->isMember())
				Részt veszek:
				<select name="participate" id="participate" class="form-control" style="width:90px;display: inline-block;" onchange="change()">
					<option value="-1" @if($participate==-1) selected @endif>válassz</option>
					<option value="1" @if($participate==1) selected @endif>igen</option>
					<option value="0" @if($participate==0) selected @endif>nem</option>
				</select>
				<br>
				<div id="participants" style="display:<?=$participate!=1?'block':'none'?>;"><?=$participants?'Ott lesznek: '.$participants:''?></div>
				<div id="participants-with-me" style="display:<?=$participate==1?'block':'none'?>;">Ott lesznek: {!! $participants_with_me !!}</div>
				<div id="no-participants" style="display:<?=$participate!=0?'block':'none'?>;"><?=$no_participants?'Jelezték, hogy nem lesznek ott: '.$no_participants:''?></div>
				<div id="no-participants-with-me" style="display:<?=$participate==0?'block':'none'?>;">Jelezték, hogy nem lesznek ott: {!! $no_participants_with_me !!}</div>
				<br>
			@endif
			@if (Auth::check() && $event->isGroupEvent() && $event->visibility!='group')
				<div class="flash-message alert alert-info" style="display:none;"></div>
				<label for="invited_user">Személyek meghívása az eseményre, akik nem tagjai a csoportnak</label>
				<div class="row">
					<div class="form-group col-sm-4">
						<select id="invited_user" name="invited_user" class="form-control">
							<option value=""></option>
							@foreach($event->group->no_group_members_list as $key => $val)
								<option value="{{ $key }}">{{ $val }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-3">
						<button type="button" id="invite_btn" class="btn btn-default" onclick="invite()">Meghív</button><span id="message_is_sending" style="display: none; font-style: italic;"> ... folyamatban</span>
					</div>
				</div>
			@endif
			@if ($event->isGroupEvent() && $users_read_it && $event->group->isAdmin())
				Látta: {!! $users_read_it !!}
			@endif
		</div>
	</div>
	@if(Auth::check())
		@include('comments._show', ['comments' => $comments, 'can_comment'=>!$event->isGroupEvent() || $event->group->isActive()] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
            'commentable_type'	=>'Event',
            'commentable_url'	=>'esemeny/'.$event->id.'/'.$event->slug,
            'commentable_id'	=>$event->id,
            'name'				=>$event->user->name,
            'email'				=>$event->user->email
        ] )

	<script>
		$('#invited_user').select2({
			placeholder: 'Írd be a nevet',
			"language": {
				"noResults": function(){
					return "Nincs találat";
				}
			}
		});

		function invite(){

			if( $('#invited_user').val()!=0) {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

				var name = $( "#invited_user option:selected" ).text();

				$("#invite_btn").prop('disabled', true);
				$("#message_is_sending").show();

				$.ajax({
					type: "POST",
					url: '{{url('esemeny')}}/{{$event->id}}/invite',
					data: {
						_token: CSRF_TOKEN,
						invited_user: $('#invited_user').val()
					},
					success: function(data) {
						if(data['status']=='success') {
							$("#message_is_sending").hide();
							$('div.flash-message').html("Meghívó elküldve: "+name);
							$('div.flash-message').show();
							setTimeout(function(){ $('div.flash-message').hide(); }, 4000);
							$("#invite_btn").prop('disabled', false);
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		}

		function change(){
			var participate = $( "#participate" ).val();

			//nem válaszoltam
			if(participate==-1) {
				$("#participants").show();
				$("#participants-with-me").hide();
				$("#no-participants").show();
				$("#no-participants-with-me").hide();
			}

			//nem veszek rész
			if(participate==0) {
				$("#participants").show();
				$("#participants-with-me").hide();
				$("#no-participants").hide();
				$("#no-participants-with-me").show();
			}

			//részt veszek
			if(participate==1) {
				$("#participants").hide();
				$("#participants-with-me").show();
				$("#no-participants").show();
				$("#no-participants-with-me").hide();
			}

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{url('esemeny')}}/{{$event->id}}/participate',
				data: {
					_token: CSRF_TOKEN,
					participate: participate
				},
				success: function(data) {
					if(data['status']=='success') {	}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}
	</script>
	@endif
@endsection