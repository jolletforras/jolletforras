		<div class="form-group">
			<label for="title">Megnevezés*:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($project)) {{old('title',$project->title)}} @else {{old('title')}} @endif" id="title">
		</div>
	
		<div class="form-group">
			<label for="body">Leírás*:</label>
			<textarea name="body" class="form-control" required="required" rows="6">@if(isset($project)) {{$project->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="looking_for">Milyen tudású/képességű emberek hiányoznak*?</label>
			<textarea class="form-control" required="required" rows="6" name="looking_for">@if(isset($project)) {{$project->looking_for}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék a kezdeményezéssel kapcsolatban*:</label>
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
			<input name="public" type="checkbox" value="1" @if(isset($project) && $project->public)  checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha nyilvános a kezdeményezésed, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

