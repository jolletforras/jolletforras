		@csrf

		<div class="form-group">
			<label for="name">Cím*:</label>
			<input class="form-control" required="required" name="title" type="text" value="@if(isset($category)){{old('title',$category->title)}}@else{{old('title')}}@endif" id="title">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
			<a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. facebook) megosztásra kerül a témakör.</div>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($category)) {{old('meta_description',$category->meta_description)}}@else{{old('meta_description')}} @endif" id="meta_description">
		</div>

		<div class="form-group">
			<label for="body">Miről szól ez a témakör, milyen írások tartoznak hozzá?*</label>
			<textarea class="form-control" required="required" rows="5" name="body" cols="50">@if(isset($category)){{old('body',$category->body)}}@else{{old('body')}}@endif</textarea>
		</div>



