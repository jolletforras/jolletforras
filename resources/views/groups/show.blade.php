@extends('layouts.app')

@section('content')
	<div class="flash-message alert alert-info" style="display:none;"></div>

	@include('groups._group_menu')
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
       	<p>
			@if ($is_admin)
				<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a>
			@endif
		</p>
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
		@include('groups._members')
		@include('groups._tags')
		@include('groups._admins')
		@if($group->city!='')
			<p><b>Hely</b>: {{$group->location()}}</p>
		@endif
		<p><i>Létrehozva: {{ $group->created_at }}, módosítva:  {{ $group->updated_at }}</i></p>
		@if ($is_admin)
		{!! Form::model($group, ['method' => 'put', 'route' => ['csoport.update', $group->id]]) !!}
		{!! Form::label('admin_list','Csoport kezelők felvétele, módosítása') !!}
		<div class="row">
			<div class="form-group col-sm-4">
				{!! Form::select('admin_list[]', $members, null, ['id' =>'admin_list','class'=>'form-control', 'multiple']) !!}
			</div>
			<div class="form-group col-sm-1">
				<button type="button" class="btn btn-default" onclick="saveAdmin()">Ment</button>
			</div>
		</div>
		{!! Form::close() !!}
		{!! Form::label('noadmin_list','Csoporttag kiléptetés') !!}
		<div class="row">
			<div class="form-group col-sm-4">
				{!! Form::select('noadmin_list[]',  $noadmins, null, ['id' =>'noadmin_list','class'=>'form-control', 'multiple']) !!}
			</div>
			<div class="form-group col-sm-1">
				<button type="button" class="btn btn-default" onclick="removeMember()">Kiléptet</button>
			</div>
		</div>
		@endif
		@if ($is_member)
		{!! Form::label('nogroupmember_list','Meghívás a csoportba') !!}
		<div class="row">
			<div class="form-group col-sm-4">
			{!! Form::select('nogroupmember',  ['0'=>'. . .']+$nogroupmembers->toArray(), null, ['id' =>'nogroupmember','class'=>'form-control']) !!}
			</div>
			<div class="form-group col-sm-1">
				<button type="button" class="btn btn-default" onclick="invite()">Meghív</button>
			</div>
		</div>
		@endif
		@if ($is_member && !$is_admin)
		<a href="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/kilepes" type="submit" class="btn btn-default"><i class="fa fa-sign-out" aria-hidden="true"></i>Kilépek a csoportból</a>
		@endif

	</div>
	@if (!$is_member)
	<div class="inner_box" style="margin-top:6px;font-size: 16px;">
		<p>Amennyiben szeretnél te is tagja lenni a csoportnak, először olvasd el a csoport megállapodását és ha azokat el tudod fogadodni kattints a csatlakozás gombra.</p>
		<p>
			<b>Csoport megállapodás:</b><br>
			{!! nl2br($group->agreement) !!}
		</p>
		<form class="form-horizontal" role="form" method="POST" action="{{url('csoport')}}/{{$group->id}}/{{$group->slug}}/csatlakozas">
			{!! csrf_field() !!}

			<p><input type="checkbox" name="accept" required style="width:18px;height:18px;"><span style="padding-left: 10px;">Elolvastam és elfogadom a csoport megállapodását.</span></p>
			<p><button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-user"></i>Csatlakozás</button></p>
		</form>
	</div>
	@endif
@endsection

@section('footer')
	<script>
		$('#admin_list').select2({
			placeholder: 'Írd be ide a csoport kezelők nevét',
			admin: true
		});

		$('#noadmin_list').select2({
			placeholder: 'Írd be a nevet',
			"language": {
				"noResults": function(){
					return "Nincs több csoporttag";
				}
			}
		});

		$('#nogroupmember').select2({
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
					noadmin_list: $('#noadmin_list').val()
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

			if( $('#nogroupmember').val()!=0) {
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

				var name = $( "#nogroupmember option:selected" ).text();

				$.ajax({
					type: "POST",
					url: '{{url('csoport')}}/{{$group->id}}/invite',
					data: {
						_token: CSRF_TOKEN,
						nogroupmember: $('#nogroupmember').val()
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