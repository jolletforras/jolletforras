@extends('layouts.app')

@section('content')
			@if(session()->has('warning'))
	            <div class="panel panel-warning">
	            	<div class="panel-heading">
						@if($user->incompleteProfile())
							Az adatlapon kérlek, a következő adatokat add meg:
							<ul>
							@if(strlen($user->name)<5)
								<li>Teljes név (minimum 5 karakter)</li>
							@endif

							@if(strlen($user->location)=='')
									<li>Lakóhelyed</li>
							@endif

							@if(empty($user->lat) || empty($user->lng))
								<li>Helyes irányítószám</li>
							@endif

							@if(strlen($user->city)=='')
								<li>Közeli nagy település</li>
							@endif

							@if(strlen($user->introduction)<config('constants.LENGTH_INTRO'))
								<li>Bemutatkozás (minimum {{config('constants.LENGTH_INTRO')}} karakter - most ennyi van: {{strlen($user->introduction)}})</li>
							@endif

							@if(strlen($user->intention)<config('constants.LENGTH_INTENTION'))
								<li>Miért jöttél a portálra? (minimum {{config('constants.LENGTH_INTENTION')}} karakter  - most ennyi van: {{strlen($user->intention)}})</li>
							@endif

							@if(strlen($user->interest)<config('constants.LENGTH_INTEREST'))
								<li>Mi lelkesít, amivel a következő hónapokban foglalkozni szeretnél? (minimum {{config('constants.LENGTH_INTEREST')}} karakter  - most ennyi van: {{strlen($user->interest)}})</li>
							@endif

							@if(count($user->tags)==0)
								<li>Legalább egy címke (válassz egyet a felsoroltak közül vagy vegyél fel újat, ha a felsorolásban nem szerepel)</li>
							@endif
						</ul>
						@endif

						@if(!$user->incompleteProfile() && $user->has_photo==0)
								Minden szükséges adatot megadtál. Már csak egy kép feltöltése szükséges ahhoz, hogy a regisztrációdat befejezd és használhasd a portál lehetőségeit.<br>
								A legegyszerűbb, ha máshol már használt profilképet töltesz fel ide is. <br><br>
							<a href="{{ url('/profilom/feltolt_profilkep') }}" class="btn btn-primary">Kép feltöltése</a>
						@endif
	            	</div>
	            </div>
	        @endif
			<!--@include('profiles.partials.profile_menu')-->
            <div class="panel panel-default">
				<div class="panel-heading">
					<h2>Adatlap kitöltése</h2>
				</div>
                <div class="panel-body">
					@include('errors.list')
					
					{!! Form::model($user, ['method' => 'put', 'route' => ['profil.update', $user->id]]) !!}
						<div class="form-group">
							{!! Form::label('name','Teljes név*') !!}
							{!! Form::text('name', null, ['class'=>'form-control', 'required' => 'required']) !!}
						</div>

						<div class="form-group">
							<b>Lakóhely*</b>
							<a href="#location_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="location_info" class="collapse info">Budapest esetén a kerületet add meg itt</div>
							{!! Form::text('location', null, ['class'=>'form-control']) !!}
						</div>	

						<div class="form-group">
							<b>Irányítószám*</b>
							<a href="#zip_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="zip_info" class="collapse info">A térképre való elhelyezésed miatt szükséges</div>
							{!! Form::text('zip_code', $user->zip_code, ['class'=>'form-control', 'maxlength'=>'4']) !!}
						</div>

						<div class="form-group">
							<b>Közeli nagy település*</b>
							<a href="#city_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="city_info" class="collapse info">Település szerinti keresés miatt szükséges</div>
							{!! Form::select('city', [null=>'']+config('constants.CITY'), $user->city, ['class'=>'form-control']) !!}
						</div>

                        <div class="form-group">
                            {!! Form::label('webpage_name','Weboldalad neve') !!}
							<a href="#webpage_name_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="webpage_name_info" class="collapse info">Ha van saját weboldalad, amit szívesen megmutatnál a portálon regisztráltaknak, írd ide. Ha több oldalad is van, azt az egyet írd ide, amit leginkább ide kapcsolódónak gondolsz.</div>
                            {!! Form::text('webpage_name', $user->webpage_name, ['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('webpage_url','Weboldalad linkje') !!}
							<a href="#webpage_url_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="webpage_url_info" class="collapse info">A böngésződ szerkesztőlécéből másold be a hivatkozást.</div>
                            {!! Form::text('webpage_url', $user->webpage_url, ['class'=>'form-control']) !!}
                        </div>

						<div class="form-group">
							<b>Bemutatkozás*</b>
							<a href="#intro_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="intro_info" class="collapse info">
								Mi mindent csináltál eddig és mivel foglalkozol most? Írj bátran mindarról, amit erre a portálra tartozónak gondolsz.
							</div>
							{!! Form::textarea('introduction', null, ['class'=>'form-control', 'rows'=>4]) !!}
						</div>

						<div class="form-group">
							<b>Miért jöttél a portálra?*</b>
							<a href="#intention_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="intention_info" class="collapse info">
								Mit szeretnél itt csinálni, milyen reményeid vannak az oldallal kapcsolatban?
							</div>
							{!! Form::textarea('intention', null, ['class'=>'form-control', 'rows'=>4]) !!}
						</div>

						<div class="form-group">
							<b>Mi lelkesít, amivel a következő hónapokban foglalkozni szeretnél?*</b>
							<a href="#interest_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="interest_info" class="collapse info">Itt írhatsz konkrét kezdeményezéseidről, terveidről, amelyekkel a társadalmi jólléthez szeretnél hozzájárulni.</div>
							{!! Form::textarea('interest', null, ['class'=>'form-control', 'rows'=>4]) !!}
						</div>

						<div class="form-group">
							<b>Itt add meg címkékkel, hogy mi az a jártasság, tudás, tapasztalat amivel mások szolgálatára lehetsz*</b>
							<a href="#skill_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="skill_info" class="collapse info">Miben van jártasságod, mit tudnál hozzáadni egy közösséghez? Címkét úgy tudsz felvenni, hogy elkezded írni amit fel szeretnél venni. A megjelenő listában rákattintasz a megfelelőre. Ha nincs olyan, akkor írd be teljesen a címkéd, majd kattints a listában rá.</div>
							{!! Form::select('tag_list[]', $tags, null, ['id' =>'tag_list', 'class'=>'form-control', 'multiple']) !!}
						</div>

						<div class="form-group">
							<label>{!! Form::checkbox('public', $user->public, ['class'=>'form-control']) !!}
							<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
							<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
							<div id="public_info" class="collapse info">Ha nyilvános a bemutatkozásod, akkor nem regisztráltak számára is láthatóvá válik.</div>
						</div>
						<div class="form-group">
							{!! Form::submit('Ment', array('class'=>'btn btn-primary')) !!}
						</div>
		
					{!! Form::close() !!}
                </div>
            </div>
@stop

		@section('footer')
			@include('partials.add_tag_script')
		@endsection