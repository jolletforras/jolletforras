		@csrf

		<div class="form-group">
			<label for="name">Neve*:</label>
			<input class="form-control" required="required" name="name" type="text" value="@if(isset($group)){{old('name',$group->name)}}@else{{old('name')}}@endif" id="name">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($group)) {{old('meta_description',$group->meta_description)}}@else{{old('meta_description')}} @endif" id="meta_description">
		</div>

		<div class="form-group">
			<label for="description">A csoport bemutatása, célja*:</label>
			<textarea name="description" class="form-control" required="required" rows="4">@if(isset($group)){{old('description',$group->description)}}@else{{old('description')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="agreement">Csoport megállapodás*:</label>
			<textarea name="agreement" class="form-control" required="required" rows="4">@if(isset($group)){{old('agreement',$group->agreement)}}@else{{old('agreement')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="member_info">Csoporttagok számára tájékoztató:</label>
			<textarea name="member_info" class="form-control" rows="4">@if(isset($group)){{old('member_info',$group->member_info)}}@else{{old('member_info')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="admin_info">Csoportkezelők megállapodása:</label>
			<textarea name="admin_info" class="form-control" rows="4">@if(isset($group)){{old('admin_info',$group->admin_info)}}@else{{old('admin_info')}}@endif</textarea>
		</div>

		@if(isset($group) && $group->knowledge_tab)
		<div class="form-group">
			<label for="knowledge_info">A tudástár bevezető szövege:</label>
			<textarea name="knowledge_info" class="form-control" rows="4">@if(isset($group)){{old('knowledge_info',$group->knowledge_info)}}@else{{old('knowledge_info')}}@endif</textarea>
		</div>
		@endif

		<div class="form-group">
			<label>
				<input name="ask_motivation" type="checkbox" value="1" @if(isset($group) && $group->ask_motivation) checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">A csoporthoz csatlakozni szándékozónak "Miért?" kérdés</span>
				<a href="#ask_motivation_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			</label>
			<div id="ask_motivation_info" class="collapse info">A csoporthoz való csatlakozás feltétele, hogy a csatlakozni szándékozó válaszoljon, miért szeretne a csoport tagja lenni, mi az ő személyes motivációja.</div>
		</div>

		<div class="form-group">
			<label for="webpage_name">Weboldalad neve:</label>
			<a href="#webpage_name_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			<div id="webpage_name_info" class="collapse info">Ha van saját weboldalad, amit szívesen megmutatnál a portálon regisztráltaknak, írd ide. Ha több oldalad is van, azt az egyet írd ide, amit leginkább ide kapcsolódónak gondolsz.</div>
			<input class="form-control" name="webpage_name" type="text" value="@if(isset($group)){{old('webpage_name',$group->webpage_name)}}@else{{old('webpage_name')}}@endif" id="webpage_name">
		</div>

		<div class="form-group">
			<label for="webpage_url">Weboldalad linkje:</label>
			<a href="#webpage_url_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			<div id="webpage_url_info" class="collapse info">A böngésződ szerkesztőlécéből másold be a hivatkozást.</div>
			<input class="form-control" name="webpage_url" type="text" value="@if(isset($group)){{old('webpage_url',$group->webpage_url)}}@else{{old('webpage_url')}}@endif" id="webpage_url">
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék a csoporttal kapcsolatban*:</label>
			<select id="tag_list" name="tag_list[]" class="form-control tag-list" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_tags) && in_array($key,$selected_tags)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label>
				<input @if(!empty($group->zip_code)) checked="checked" @endif name="local" id="local" type="checkbox" value="1">
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
				<input class="form-control" name="location" type="text" value="@if(isset($group)){{old('location',$group->location)}}@else{{old('location')}}@endif">
			</div>

			<div class="form-group">
				<b>Irányítószám*</b>
				<a href="#zip_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				<div id="zip_info" class="collapse info">A csoportnak a térképre való elhelyezés miatt szükséges</div>
				<input class="form-control" maxlength="4" name="zip_code" type="text" value="@if(isset($group)){{old('zip_code',$group->zip_code)}}@else{{old('zip_code')}}@endif">
			</div>

			<div class="form-group">
				<b>Közeli nagy település*</b>
				<a href="#city_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				<div id="city_info" class="collapse info">Település szerinti keresés miatt szükséges</div>
				<select class="form-control" name="city">
 					<option value=""></option>
					@foreach(constx('CITY') as $key => $val)
						<option value="{{ $key }}" @if(isset($group) && $group->city==$val) selected @endif>{{ $val }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
			<label>
				<input name="public" type="checkbox" value="1" @if(isset($group) && $group->public) checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
				<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			</label>
			<div id="public_info" class="collapse info">Ha nyilvános a csoport, akkor annak bemutatását a nem regisztráltak is megnézhetik.</div>
		</div>

		<div class="form-group">
			<label for="user_visibility">Tagok láthatósága:</label>
			<select name="user_visibility">
				@foreach($user_visibility as $key => $val)
					<option value="{{ $key }}"  @if(isset($group) && $key == $group->user_visibility) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
			<a href="#user_visibility" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="user_visibility" class="collapse info">Ha a láthatóság "csoport", akkor csak a csoport tagok látják, hogy kik a tagjai a csoportnak; ha "portál" akkor minden regisztrált látja; ha "nyilvános" akkor a nem regisztráltak számára is látható, de csak azok a tagok akiknek nyilvános az adatlapja.</div>
		</div>

		<div class="form-group">
			<label>
				<input name="inactive" type="checkbox" value="1" @if(isset($group) && $group->status=='inactive') checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Inaktív</span>
				<a href="#inactive_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
			</label>
			<div id="inactive_info" class="collapse info">Ha inaktív a csoport nem látható csoportok között.</div>
		</div>