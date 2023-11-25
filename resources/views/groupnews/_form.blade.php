        @csrf

		@if(isset($group_id))
		<input name="group_id" type="hidden" value="{{$group_id}}">
		@endif

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($news)) {{$news->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
			<a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. facebook) megosztod a híreket. Ennek akkor van jelentősége, ha nyilvános a hírek, vagyis nem regisztráltak számára is elérhető.</div>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($news)) {{$news->meta_description}} @endif" id="meta_description">
		</div>

		<div class="form-group">
			<label for="body">Szöveg:</label>
			<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($news)) {{$news->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="visibility">Láthatóság:</label>
			<select name="visibility">
				@foreach($visibility_options as $key => $val)
					<option value="{{ $key }}"  @if(isset($news) && $key == $news->visibility) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha a láthatóság "portál", akkor csak a portálon regisztráltak számára elérhető a hír; ha "nyilvános" akkor a nem regisztráltak számára is látható, de csak abban az esetben ha a csoport nyilvánosan elérhető.</div>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék:</label>
			<a href="#tag_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="tag_info" class="collapse info">Ha a láthatósága a hírnek "portál" vagy "nyilvános", akkor érdemes címkét beállítani, hogy a portál Hírek oldalon címke alapján rá lehessen keresni. A csoport neve lehet egy fontos címke, mivel az alapján a csoport összes hírére rá tudnak találni a címke megadásával.</div>
			<select id="tag_list" name="tag_list[]" class="form-control tag-list" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_tags) && in_array($key,$selected_tags)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

		@section('footer')
			@include('partials.add_tag_script')
		@endsection
	</div>
</div>		
		
