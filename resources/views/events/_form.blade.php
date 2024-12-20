		<input type="hidden" name="group_id" value="{{ $group_id }}">

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($event)) {{$event->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
            <a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
            <div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. facebook) megosztásra kerül az esemény. Ennek akkor van jelentősége, ha nyilvános az esemény, vagyis nem regisztráltak számára is elérhető.</div>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($event)) {{$event->meta_description}} @endif" id="meta_description">
		</div>

		<div class="form-group">
			<label class="col-form-label" for="status">Időpont:</label>
			<input type="text" class="form-control datepicker-event" required="required" placeholder="Időpont" name="time" value="@if(isset($event)){{$event->time}}@endif" data-language="hu" data-position="bottom left" data-date-format="yyyy-mm-dd" style="width:150px;"/>
		</div>


		<div class="form-group">
			<label class="col-form-label" for="status">Érvényessége:</label>
			<input type="text" class="form-control datepicker-expiration" required="required" placeholder="Dátum" name="expiration_date" value="@if(isset($event)){{$event->expiration_date}}@endif" data-language="hu" data-position="bottom left" data-date-format="yyyy-mm-dd" style="width:150px;"/>
		</div>

		<div class="form-group">
			<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($event)) {{$event->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="visibility">Láthatóság:</label>
			<select name="visibility">
				@foreach($visibility as $key => $val)
					<option value="{{ $key }}"  @if(isset($event) && $key == $event->visibility) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			@if($group_id==0)
				<div id="public_info" class="collapse info">Ha nyilvános az esemény, akkor a nem regisztráltak számára is elérhető.</div>
			@else
				<div id="public_info" class="collapse info">Ha a láthatóság "csoport", akkor csak a csoport számára elérhető az esemény; ha "az oldalon" akkor minden regisztrált számára; ha "nyilvános" akkor a nem regisztráltak számára is látható.</div>
			@endif
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="Mentés">
		</div>

