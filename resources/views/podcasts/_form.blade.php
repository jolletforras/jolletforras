        @csrf

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($podcast)) {{$podcast->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
            <a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
            <div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. facebook) megosztásra kerül ez az oldal. Ennek akkor van jelentősége, ha nyilvános ez a podcast, vagyis nem regisztráltak számára is elérhető.</div>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($podcast)) {{$podcast->meta_description}} @endif">
		</div>

		<div class="form-group">
			<label for="url">Podcast url:</label>
			<input class="form-control"  required="required" name="url" type="text" maxlength="255" value="@if(isset($podcast)) {{$podcast->url}} @endif">
		</div>

		<div class="form-group">
			<label for="url">Esemény azonosító:</label>
			<input class="form-control"  required="required" name="event_id" type="text" maxlength="10" value="@if(isset($podcast)) {{$podcast->event_id}} @endif">
		</div>

		<div class="form-group">
			<label for="url">Csoport azonosító (ha van):</label>
			<input class="form-control" name="group_id" type="text" maxlength="10" value="@if(isset($podcast)) {{$podcast->group_id}} @endif">
		</div>


		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

	</div>
</div>		
		
