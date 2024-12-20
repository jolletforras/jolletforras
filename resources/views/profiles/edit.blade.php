@extends('layouts.app')

@section('content')
			@include('partials.tinymce_just_link_js')

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
								<li>Miért jöttél a Jóllét Forrás oldalra? (minimum {{config('constants.LENGTH_INTENTION')}} karakter  - most ennyi van: {{strlen($user->intention)}})</li>
							@endif

							@if(strlen($user->interest)<config('constants.LENGTH_INTEREST'))
								<li>Mi lelkesít, amivel a következő hónapokban foglalkozni szeretnél? (minimum {{config('constants.LENGTH_INTEREST')}} karakter  - most ennyi van: {{strlen($user->interest)}})</li>
							@endif

							@if(count($user->skill_tags)==0)
								<li>Legalább egy címke azzal kapcsolatban amiben jártasságod, tudásod, tapasztalatod van, amivel mások szolgálatára lehetsz (válassz egyet a felsoroltak közül vagy vegyél fel újat, ha a felsorolásban nem szerepel)</li>
							@endif

							@if(count($user->interest_tags)==0)
								<li>Legalább egy címke azzal kapcsolatban ami érdekel, amiben fejlődni szeretnél (válassz egyet a felsoroltak közül vagy vegyél fel újat, ha a felsorolásban nem szerepel)</li>
							@endif
						</ul>
						@endif

						@if(!$user->incompleteProfile() && $user->has_photo==0)
								Minden szükséges adatot megadtál. Már csak egy kép feltöltése szükséges ahhoz, hogy a regisztrációdat befejezd és használhasd a Jóllét Forrás oldal lehetőségeit.<br>
								A legegyszerűbb, ha máshol már használt profilképet töltesz fel ide is. <br><br>
							<a href="{{ url('/profilom/feltolt_profilkep') }}" class="btn btn-primary">Kép feltöltése</a>
						@endif
	            	</div>
	            </div>
	        @endif
            <div class="panel panel-default">
				<div class="panel-heading">
					<h2>Adatlap kitöltése</h2>
				</div>
                <div class="panel-body">
					@include('errors.list')
					<form method="POST" action="{{url('profil')}}/{{$user->id}}/modosit" accept-charset="UTF-8">

						@csrf

						<div class="form-group">
							<label for="name">Teljes név*:</label>
							<input class="form-control" maxlength="30" required="required" name="name" type="text" value="{{old('name',$user->name)}}" id="name">
						</div>

						<div class="form-group">
 							<b>Lakóhely*</b>
							<a href="#location_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="location_info" class="collapse info">Budapest esetén a kerületet add meg itt</div>
							<input class="form-control" maxlength="30" name="location" type="text" value="{{old('location',$user->location)}}">
						</div>	

						<div class="form-group">
							<b>Irányítószám*</b>
							<a href="#zip_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="zip_info" class="collapse info">A térképre való elhelyezésed miatt szükséges</div>
							<input class="form-control" maxlength="4" name="zip_code" type="text" value="{{old('zip_code',$user->zip_code)}}">
						</div>

						<div class="form-group">
							<b>Közeli nagy település*</b>
							<a href="#city_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="city_info" class="collapse info">Település szerinti keresés miatt szükséges</div>
							<select class="form-control" name="city">
								<option value=""></option>
								@foreach(constx('CITY') as $key => $val)
									<option value="{{ $key }}" @if(old('city',$user->city)==$val) selected @endif>{{ $val }}</option>
								@endforeach
							</select>
						</div>

                        <div class="form-group">
							<label for="webpage_name">Weboldalad neve</label>
							<a href="#webpage_name_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="webpage_name_info" class="collapse info">Ha van saját weboldalad, amit szívesen megmutatnál a Jóllét Forrás oldalon regisztráltaknak, írd ide. Ha több oldalad is van, akkor egymástól vesszővel elválasztva add meg.</div>
							<input class="form-control" maxlength="200" name="webpage_name" type="text" value="{{old('webpage_name',$user->webpage_name)}}" id="webpage_name">
                        </div>

                        <div class="form-group">
							<label for="webpage_url">Weboldalad linkje</label>
							<a href="#webpage_url_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="webpage_url_info" class="collapse info">A böngésződ szerkesztőlécéből másold be a hivatkozást. Ha több oldalad van, akkor a linkeket egymástól vesszővel választva add meg. Ugyanabban a sorrendbe tedd meg, ahogy a weboldalaid nevét adtad meg.</div>
							<input class="form-control" maxlength="200" name="webpage_url" type="text" value="{{old('webpage_url',$user->webpage_url)}}" id="webpage_url">
                        </div>

						<div class="form-group">
							<b>Bemutatkozás*</b>
							<a href="#intro_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="intro_info" class="collapse info">
								Mi mindent csináltál eddig és mivel foglalkozol most? Írj bátran mindarról, amit erre a Jóllét Forrás oldalra tartozónak gondolsz.
							</div>
							<textarea class="form-control" rows="5" name="introduction" cols="50">{{old('introduction',$user->introduction)}}</textarea>
						</div>

						<div class="form-group">
							<b>Miért jöttél a Jóllét Forrás oldalra?*</b>
							<a href="#intention_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="intention_info" class="collapse info">
								Mit szeretnél itt csinálni, milyen reményeid vannak az oldallal kapcsolatban?
							</div>
							<textarea class="form-control" rows="5" name="intention" cols="50">{{old('intention',$user->intention)}}</textarea>
						</div>

						<div class="form-group">
							<b>Mi lelkesít, amivel a következő hónapokban foglalkozni szeretnél?*</b>
							<a href="#interest_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="interest_info" class="collapse info">Itt írhatsz konkrét kezdeményezéseidről, terveidről, amelyekkel a közös jólléthez szeretnél hozzájárulni.</div>
							<textarea class="form-control" rows="5" name="interest" cols="50">{{old('interest',$user->interest)}}</textarea>
						</div>

						<div class="form-group">
							<b>Itt add meg címkékkel, hogy mi az a jártasság, tudás, tapasztalat, amivel mások szolgálatára lehetsz*</b>
							<a href="#skill_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="skill_info" class="collapse info">Miben van jártasságod, mit tudnál hozzáadni egy közösséghez? Címkét úgy tudsz felvenni, hogy elkezded írni amit fel szeretnél venni. A megjelenő listában rákattintasz a megfelelőre. Ha nincs olyan, akkor írd be teljesen a címkéd, majd kattints a listában rá.</div>
							<select id="skill_tag_list" name="skill_tag_list[]" class="form-control tag-list" multiple>
								@foreach($skill_tags as $key => $val)
									<option value="{{ $key }}" @if(isset($selected_skill_tags) && in_array($key,old('skill_tag_list',$selected_skill_tags))) selected @endif>{{ $val }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<b>Itt add meg címkékkel, hogy mi az ami érdekel, amiben fejlődni szeretnél*</b>
							<a href="#interest_tag_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
							<div id="interest_tag_info" class="collapse info">Miben szeretnéd elmélyíteni a tudásod ami a közösség szolgálatára lehet? Címkét úgy tudsz felvenni, hogy elkezded írni amit fel szeretnél venni. A megjelenő listában rákattintasz a megfelelőre. Ha nincs olyan, akkor írd be teljesen a címkéd, majd kattints a listában rá.</div>
							<select id="interest_tag_list" name="interest_tag_list[]" class="form-control tag-list" multiple>
								@foreach($interest_tags as $key => $val)
									<option value="{{ $key }}" @if(isset($selected_interest_tags) && in_array($key,old('interest_tag_list',$selected_interest_tags))) selected @endif>{{ $val }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<input name="public" type="checkbox" value="1" @if((!old() && $user->public) || old('public')) checked @endif>
							<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
							<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
							<div id="public_info" class="collapse info">Ha nyilvános a bemutatkozásod, akkor nem regisztráltak számára is láthatóvá válik.</div>
						</div>
						<div class="form-group">
							<input class="btn btn-primary" type="submit" value="Mentés">
						</div>

					</form>
                </div>
            </div>
@stop

		@section('footer')
			@include('partials.add_tag_script')
		@endsection