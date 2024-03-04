@extends('layouts.app')

@section('content')
	<?php
			$is_member = $project->isMember();
			$is_admin = $project->isAdmin();
			$is_owner = $project->isOwner();
	?>
	@include('projects._project_menu')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<p>
			@if ($is_owner || $is_admin)
				<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a>
				<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/kepfeltoltes" type="submit" class="btn btn-default">Képfeltöltés</a>
			@endif
			@if($is_member && !$is_owner)
				<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/kilep" type="submit" class="btn btn-default">Kilépek</a>
			@endif
		</p>
		@if(file_exists(public_path('images/projects/'.$project->id.'.jpg')))
			<p style="text-align: center;"><img src="{{ url('/images/projects') }}/{{ $project->id}}.jpg?{{$project->photo_counter}}" style="max-width: 50%;"></p>
		@endif
			<p>{!! nl2br($project->body) !!}</p>
			<p>{!! nl2br($project->my_undertake) !!}</p>
			<p>{!! nl2br($project->looking_for) !!}</p>
			<p><b>Felvette: </b><a href="{{ url('profil',$project->user->id) }}/{{$project->user->slug}}">{{ $project->user->name }}</a>, {{ $project->created_at }}</p>
			@include('projects._admins')
			@include('projects._members')
			@include('projects._tags')
		@if ($is_owner)
			<div class="flash-message alert alert-info" style="display:none;"></div>
			<label for="admin_list">Kezelők felvétele, módosítása</label>
			<div class="row">
				<div class="form-group col-sm-6">
					<select id="admin_list" name="admin_list[]" class="form-control" multiple>
						@foreach($members as $key => $val)
							<option value="{{ $key }}" @if(isset($admins) && in_array($key,$admins) && $key!=Auth()->user()->id) selected @endif>{{ $val }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-sm-3">
					<button type="button" class="btn btn-default" onclick="saveAdmin()">Ment</button>
				</div>
			</div>
		@endif

		@if ($is_admin)
			<label for="remove_member">Résztvevő kiléptetés</label>
			<div class="row">
				<div class="form-group col-sm-6">
					<select id="remove_member" name="remove_member" class="form-control">
						<option value=""></option>
						@foreach($noadmins as $key => $val)
							<option value="{{ $key }}">{{ $val }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-sm-3">
					<button type="button" class="btn btn-default" onclick="removeMember()">Kiléptet</button>
				</div>
			</div>

			<label for="invited_user">Meghívás a kezdeményezésbe</label>
			<div class="row">
				<div class="form-group col-sm-6">
					<select id="invited_user" name="invited_user" class="form-control">
						<option value=""></option>
						@foreach($nomembers as $key => $val)
							<option value="{{ $key }}">{{ $val }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-sm-3">
					<button type="button" id="invite_btn" class="btn btn-default" onclick="invite()">Meghív</button><span id="message_is_sending" style="display: none; font-style: italic;"> ... folyamatban</span>
				</div>
			</div>
		@endif

		@if (!$project->isMember())
			<div class="inner_box" style="margin-top:6px;">
				<p>Amennyiben résztvevője vagy a kezdeményezésnek, kattints a "Résztvevő vagyok" gombra. Ha szeretnél részt venni a kezdeményezésben, vedd fel a kapcsolatot a kezdeményezés kezelőivel.</p>


				<form class="form-horizontal" role="form" method="POST" action="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/resztvevo_vagyok">
					@csrf

					<p><button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-user"></i>Résztvevő vagyok</button></p>
				</form>
			</div>
		@endif
	</div>

	@if(Auth::user()->isGroupAdmin())
		<p class="narrow-page"><button class="btn btn-default" type="button" onclick="get_group_admin_block()" id="btn_group_admin_block"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Csoporthoz való hozzáadás/törlés</button></p>
		<div class="inner_box narrow-page" id="group_admin_block" style="display: none;">... hamarosan betölt</div>
	@endif

	@include('comments._show', ['comments' => $comments] )
@endsection

@section('footer')
	@include('partials.comment_script', [
        'commentable_type'	=>'Project',
        'commentable_url'	=>'kezdemenyezes/'.$project->id.'/'.$project->slug,
        'commentable_id'	=>$project->id,
        'name'				=>$project->user->name,
        'email'				=>$project->user->email
    ] )

	@include('partials.post_group_script', [
    	'post_type'	=>'project',
    	'post_id'	=>$project->id,
	] )

	<script>
		$('#admin_list').select2({
			placeholder: 'Írd be ide a kezelők nevét',
			admin: true,
			"language": {
				"noResults": function(){
					return "Nincs találat";
				}
			}
		});

		$('#invited_user').select2({
			placeholder: 'Írd be a nevet',
			"language": {
				"noResults": function(){
					return "Nincs találat";
				}
			}
		});


		function removeMember(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{url('kezdemenyezes')}}/{{$project->id}}/removemember',
				data: {
					_token: CSRF_TOKEN,
					remove_member: $('#remove_member').val()
				},
				success: function(data) {
					if(data['status']=='success') {
						location.reload();
					}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}

		function saveAdmin(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{url('kezdemenyezes')}}/{{$project->id}}/saveadmin',
				data: {
					_token: CSRF_TOKEN,
					admin_list: $('#admin_list').val()
				},
				success: function(data) {
					if(data['status']=='success') {
						$('div.flash-message').html("A szerkesztők elmentve!");
						$('div.flash-message').show();
						setTimeout(function(){ $('div.flash-message').hide(); }, 3000);
					}
					if(data['status']=='error') {
						$('div.flash-message').html(data['message']);
						$('div.flash-message').show();
						setTimeout(function(){ window.location.href = "{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}"; }, 3000);
					}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}

		function invite(){

			if( $('#invited_user').val()!=0) {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

				var name = $( "#invited_user option:selected" ).text();

				$("#invite_btn").prop('disabled', true);
				$("#message_is_sending").show();

				$.ajax({
					type: "POST",
					url: '{{url('kezdemenyezes')}}/{{$project->id}}/invite',
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
	</script>
@endsection