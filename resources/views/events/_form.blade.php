		<input type="hidden" name="group_id" value="{{ $group_id }}">

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" value="@if(isset($event)) {{$event->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($event)) {{$event->body}} @endif</textarea>
		</div>

		<div class="form-group">
			Láthatóság:
			<select name="visibility">
				@foreach($visibility as $key => $val)
					<option value="{{ $key }}"  @if(isset($event) && $key == $event->visibility) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			@if($group_id==0)
				<div id="public_info" class="collapse info">Ha nyilvános az esemény, akkor a nem regisztráltak számára is elérhető.</div>
			@else
				<div id="public_info" class="collapse info">Ha a láthatóság "csoport", akkor csak a csoport számára elérhető az esemény; ha "portál" akkor minden regisztrált számára; ha "nyilvános" akkor a nem regisztráltak számára is látható.</div>
			@endif
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="Mentés">
		</div>

