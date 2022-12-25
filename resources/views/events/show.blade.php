@extends('layouts.app')
@section('title'){{ $event->title }}@endsection
@section('description'){{ $event->meta_description }}@endsection
@section('url'){{url('esemeny')}}/{{$event->id}}/{{$event->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}" />
@endsection

@section('content')
	<div class="flash-message alert alert-info" style="display:none;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>{{ $event->title }}</h2>@if($event->isGroupEvent())&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{url('csoport')}}/{{$event->group->id}}/{{$event->group->slug}}/esemenyek"> << {{$event->group->name}}</a>@endif
		</div>
		<div class="panel-body">
			@if ($has_access)
				<a href="{{url('esemeny')}}/{{$event->id}}/{{$event->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			@endif
			{!! $event->body !!}
			@include('partials.author', ['author'=>'Eseményt felvette: ','obj'=>$event])
			@if ($event->isGroupEvent() && $event->visibility!='group' && $event->group->isAdmin())
				<label for="invited_user">Meghívás az eseményre</label>
				<div class="row">
					<div class="form-group col-sm-4">
						<select id="invited_user" name="invited_user" class="form-control">
							<option value=""></option>
							@foreach($event->group->no_group_members_list as $key => $val)
								<option value="{{ $key }}">{{ $val }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-sm-1">
						<button type="button" class="btn btn-default" onclick="invite()">Meghív</button>
					</div>
				</div>
			@endif
			@if ($event->isGroupEvent() && $users_read_it && Auth::check() && Auth::user()->id==$event->user->id)
				Látta: {!! $users_read_it !!}
			@endif
		</div>
	</div>
	@if(Auth::check())
		@include('comments._show', [
		'comments' => $comments,
		'commentable_type'	=>'Event',
		'commentable_url'	=>'esemeny/'.$event->id.'/'.$event->slug,
		'commentable'	=>$event
		] )
	@endif
@endsection

@section('footer')
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

				$.ajax({
					type: "POST",
					url: '{{url('esemeny')}}/{{$event->id}}/invite',
					data: {
						_token: CSRF_TOKEN,
						invited_user: $('#invited_user').val()
					},
					success: function(data) {
						if(data['status']=='success') {
							$('div.flash-message').html("Meghívó elküldve: "+name);
							$('div.flash-message').show();
							setTimeout(function(){ $('div.flash-message').hide(); }, 4000);
						}
					},
					error: function(error){
						console.log(error.responseText);
					}
				});
			}
		}
	</script>
@endsection