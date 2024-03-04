		<div class="form-group">
			<label for="title">Megnevezés*:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($project)){{old('title',$project->title)}}@else{{old('title')}}@endif" id="title">
		</div>
	
		<div class="form-group">
			<label for="body">Mutasd be a kezdeményezésed*:</label>
			<a href="#body_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			<div id="body_info" class="collapse info">Miért indítod ezt a kezdeményezést? Milyen értékeket hordoz vagy támogat a kezdeményezésed? Hogyan szolgálja az új világ építését? Miről szól a kezdeményezés, mi az elképzelésed, megvalósítási terved?</div>
			<textarea name="body" class="form-control" required="required" rows="6">@if(isset($project)){{$project->body}}@else{{old('body')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="my_undertake">Te kezdeményezőként mit tudsz beletenni a megvalósításba?*</label>
			<textarea class="form-control" required="required" rows="6" name="my_undertake">@if(isset($project)){{$project->my_undertake}}@else{{old('my_undertake')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="looking_for">Kiket vársz társakként a kezdeményezésedhez?</label>
			<a href="#looking_for_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			<div id="looking_for_info" class="collapse info">Ennek a résznek a kitöltése nem szükséges, amennyiben már működő kezdeményezést mutatsz be, amelyhez nem keresel társakat a portálon.</div>
			<textarea class="form-control" rows="6" name="looking_for">@if(isset($project)){{$project->looking_for}}@else {{old('looking_for')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék a kezdeményezéssel kapcsolatban*:</label>
			<a href="#tag_list_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			<div id="tag_list_info" class="collapse info">A címkékben a kezdeményezés tárgya, köre szerepeljen.</div>
			<select id="tag_list" name="tag_list[]" class="form-control tag-list" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_tags) && in_array($key,$selected_tags)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label>
				<input @if(!empty($project->zip_code)) checked="checked" @endif name="local" id="local" type="checkbox" value="1">
				<span style="padding-top: 10px; font-size: 18px;">Helyi csoport</span>
				<a href="#local_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			</label>
			<div id="local_info" class="collapse info">Ezt a részt csak akkor szükséges kitöltened, ha helyi csoportról van szó.</div>
		</div>

		<div id="local-block">
			<div class="form-group">
				<b>Település*</b>
				<a href="#location_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				<div id="location_info" class="collapse info">Nagy város esetén csak a kerületet ad meg, ha csak arra kerületre vonatkozik a csoport</div>
				<input class="form-control" name="location" type="text" value="@if(isset($project)){{old('location',$project->location)}}@else{{old('location')}}@endif">
			</div>

			<div class="form-group">
				<b>Irányítószám*</b>
				<a href="#zip_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				<div id="zip_info" class="collapse info">A csoportnak a térképre való elhelyezés miatt szükséges</div>
				<input class="form-control" maxlength="4" name="zip_code" type="text" value="@if(isset($project)){{old('zip_code',$project->zip_code)}}@else{{old('zip_code')}}@endif">
			</div>

			<div class="form-group">
				<b>Közeli nagy település*</b>
				<a href="#city_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				<div id="city_info" class="collapse info">Település szerinti keresés miatt szükséges</div>
				<select class="form-control" name="city">
					<option value=""></option>
					@foreach(constx('CITY') as $key => $val)
						<option value="{{ $key }}" @if(isset($project) && $project->city==$val) selected @endif>{{ $val }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
			<label>
				<input name="inactive" type="checkbox" value="1" @if(isset($project) && $project->status=='inactive') checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Inaktív</span>
				<a href="#inactive_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			</label>
			<div id="inactive_info" class="collapse info">Ha inaktív a kezdeményezés, akkor nem látható kezdeményezések között.</div>
		</div>

		<div class="form-group">
			<input name="public" id="public" type="checkbox" value="1" @if(isset($project) && $project->public)  checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha nyilvános a kezdeményezésed, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

		<div class="form-group" id="meta-description-block" style="display: @if(isset($project) && $project->public) block @else none @endif">
			<label for="meta_description">Meta leírás:</label>
			<input class="form-control" name="meta_description" type="text" maxlength="160" value="@if(isset($project)){{old('meta_description',$project->meta_description)}}@else{{old('meta_description')}}@endif">
		</div>

		@if(isset($project) && Auth::user()->admin)
			<div class="form-group">
				<input name="approved" type="checkbox" value="1" @if($project->approved) checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Engedélyez</span>
			</div>
		@endif


