@extends('layouts.app')

@section('content')
	<div class="panel panel-default narrow-page">
		<div class="panel-heading">
			<h2>
				{{ $project->title }}
				@if($project->city!='')
					- <i style="font-weight: normal; font-size: 16px;">{{$project->get_location()}}</i>
				@endif
			</h2>
			@if(Auth::check())
				@if (Auth::user()->id==$project->user->id || $is_admin)
					<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
				@endif
				@if(Auth::user()->id!=$project->user->id && $project->isMember())
					<a href="{{url('kezdemenyezes')}}/{{$project->id}}/{{$project->slug}}/kilep" type="submit" class="btn btn-default">Kilépek</a>
				@endif
			@endif
		</div>
        <div class="panel-body">
			<p>{!! nl2br($project->body) !!}</p>
			@if(Auth::check())
				<p>{!! nl2br($project->looking_for) !!}</p>
				@include('projects._admins')
				@include('projects._members')
				@include('projects._tags')
				@if (Auth::user()->id == $project->user->id)
					<div class="flash-message alert alert-info" style="display:none;"></div>
					<label for="admin_list">Kezelők felvétele, módosítása</label>
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
			@endif
	    </div>
    </div>
	@if(Auth::check())
		@include('comments._show', ['comments' => $comments] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
            'commentable_type'	=>'Project',
            'commentable_url'	=>'kezdemenyezes/'.$project->id.'/'.$project->slug,
            'commentable_id'	=>$project->id,
            'name'				=>$project->user->name,
            'email'				=>$project->user->email
        ] )

		<script>
			$('#admin_list').select2({
				placeholder: 'Írd be ide a szerkesztők nevét',
				admin: true,
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
		</script>
	@endif
@endsection