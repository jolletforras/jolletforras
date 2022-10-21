		<div class="form-group">
			<label for="title">Megnevezés*:</label>
			<input class="form-control" required="required" name="title" type="text" value="@if(isset($project)) {{$project->title}} @endif" id="title">
		</div>
	
		<div class="form-group">
			<label for="body">Leírás*:</label>
			<textarea name="body" class="form-control" required="required" rows="6">@if(isset($project)) {{$project->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="members">Kik a tagjai a kezdeményezésnek a portálról?</label>
			<select id="member_list" name="members" class="form-control" multiple>
				@foreach($members as $key => $val)
					<option value="{{ $key }}"  @if(isset($project) && $key == $project->members) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="looking_for">Milyen tudású/képességű emberek hiányoznak*?</label>
			<textarea class="form-control" required="required" rows="6" name="looking_for">@if(isset($project)) {{$project->looking_for}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék ezekre a képességekre*:</label>
			<select id="tag_list" name="tags" class="form-control" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}"  @if(isset($project) && $key == $project->tags) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>


