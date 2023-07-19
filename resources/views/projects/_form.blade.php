		<div class="form-group">
			<label for="title">Megnevezés*:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($project)) {{old('title',$project->title)}} @else {{old('title')}} @endif" id="title">
		</div>
	
		<div class="form-group">
			<label for="body">Leírás*:</label>
			<textarea name="body" class="form-control" required="required" rows="6">@if(isset($project)) {{$project->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="member_list">Kik a tagjai a kezdeményezésnek a portálról?</label>
			<select id="member_list" name="member_list[]" class="form-control" multiple>
				@foreach($members as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_members) && in_array($key,$selected_members)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="looking_for">Milyen tudású/képességű emberek hiányoznak*?</label>
			<textarea class="form-control" required="required" rows="6" name="looking_for">@if(isset($project)) {{$project->looking_for}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék ezekre a képességekre*:</label>
			<select id="tag_list" name="tag_list[]" class="form-control tag-list" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_tags) && in_array($key,$selected_tags)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<input name="public" type="checkbox" value="1" @if(isset($project) && $project->public)  checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha nyilvános a kezdeményezésed, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

