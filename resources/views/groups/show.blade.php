@extends('layouts.app')

@section('content')
	@if($is_member || $newss->isNotEmpty())
		@include('groups._group_menu')
	@endif
	<div class="inner_box narrow-page" style="margin-top:6px;">
		@if(!$is_member && !$newss->isNotEmpty())
			<h2>
				{{ $group->name }}
				@if($group->city!='')
					- <i style="font-weight: normal; font-size: 16px;">{{$group->get_location()}}</i>
				@endif
			</h2>
		@endif
       	<p>
			@if ($is_admin)
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a>
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/kepfeltoltes" type="submit" class="btn btn-default">Képfeltöltés</a>
			@endif
		</p>
		@if(file_exists(public_path('images/groups/'.$group->id.'.jpg')))
			<p style="text-align: center;"><img src="{{ url('/images/groups') }}/{{ $group->id}}.jpg?{{$group->photo_counter}}" style="max-width: 50%;"></p>
		@endif
		<p>{!! nl2br($group->description) !!}</p>
		@if ($is_member)
		<p>
			<b>Csoport megállapodás:</b><br>
			{!! nl2br($group->agreement) !!}
		</p>
		@endif
		@if($group->webpage_url!='')
			<p><b>Weboldal:</b> <a href="{{$group->webpage_url}}" target="_blank">{{$group->webpage_name}}</a></p>
		@endif
		@include('partials.tags',['url'=>'csoport','obj'=>$group])
		@include('groups._admins')
		@if($group->city!='')
			<p><b>Hely</b>: {{$group->get_location()}}</p>
		@endif
		<p><i>Létrehozva: {{ $group->created_at }}, módosítva:  {{ $group->updated_at }}</i></p>

		<div class="flash-message alert alert-info" style="display:none;"></div>

		@if ($is_admin)
		<label for="admin_list">Csoportkezelők felvétele, módosítása</label>
		<div class="row">
			<div class="form-group col-sm-6">
				<select id="admin_list" name="admin_list[]" class="form-control" multiple>
					@foreach($members as $key => $val)
						<option value="{{ $key }}" @if(isset($admins) && in_array($key,$admins)) selected @endif>{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-3">
				<button type="button" class="btn btn-default" onclick="saveAdmin()">Ment</button>
			</div>
		</div>
		<label for="remove_member">Csoporttag kiléptetés</label>
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
		@endif
		@if ($is_member)
		<label for="invited_user">Meghívás a csoportba</label>
		<div class="row">
			<div class="form-group col-sm-6">
				<select id="invited_user" name="invited_user" class="form-control">
					<option value=""></option>
					@foreach($nogroupmembers as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-sm-3">
				<button type="button" id="invite_btn" class="btn btn-default" onclick="invite()">Meghív</button><span id="message_is_sending" style="display: none; font-style: italic;"> ... folyamatban</span>
			</div>
		</div>
		@endif
		@if ($is_member && !$is_admin)
		<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/kilepes" type="submit" class="btn btn-default"><i class="fa fa-sign-out" aria-hidden="true"></i>Kilépek a csoportból</a>
		@endif

	</div>
	@if (!$is_member)
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<p>Amennyiben szeretnél te is tagja lenni a csoportnak, először olvasd el a csoport megállapodását és ha azokat el tudod fogadodni kattints a csatlakozás gombra.</p>
		<p>
			<b>Csoport megállapodás:</b><br>
			{!! nl2br($group->agreement) !!}
		</p>


		<form class="form-horizontal" role="form" method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/csatlakozas">
			@csrf

			<p><input type="checkbox" name="accept" required style="width:18px;height:18px;"><span style="padding-left: 10px;">Elolvastam és elfogadom a csoport megállapodását.</span></p>
			<p><button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-user"></i>Csatlakozás</button></p>
		</form>
	</div>
	@endif
@endsection

@section('footer')
	<script>
		$('#admin_list').select2({
			placeholder: 'Írd be ide a csoportkezelők nevét',
			admin: true
		});

		$('#remove_member').select2({
			placeholder: 'Írd be a nevet',
			"language": {
				"noResults": function(){
					return "Nincs több csoporttag";
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

		function saveAdmin(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{url('csoport')}}/{{$group->id}}/saveadmin',
				data: {
					_token: CSRF_TOKEN,
					admin_list: $('#admin_list').val()
				},
				success: function(data) {
					if(data['status']=='success') {
						location.reload();
					}
					if(data['status']=='error') {
						$('div.flash-message').removeClass("alert-info");
						$('div.flash-message').addClass("alert-warning");
						$('div.flash-message').html(data['message']);
						$('div.flash-message').show();
						setTimeout(function(){ window.location.href = "{{url('csoport')}}/{{$group->id}}/{{$group->slug}}"; }, 3000);
					}
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		}

		function removeMember(){

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{url('csoport')}}/{{$group->id}}/removemember',
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

		function invite(){

			if( $('#invited_user').val()!=0) {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

				var name = $( "#invited_user option:selected" ).text();

				$("#invite_btn").prop('disabled', true);
				$("#message_is_sending").show();

				$.ajax({
					type: "POST",
					url: '{{url('csoport')}}/{{$group->id}}/invite',
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