				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($article)) {{$article->title}} @endif" id="title">
				</div>

				<div class="form-group">
					<label for="meta_description">Meta leírás:</label>
					<a href="#meta_description_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
					<div id="meta_description_info" class="collapse info">Az itt megadott szöveg íródik ki ha egy másik oldalon (pl. Facebook) megosztásra kerül az írás.</div>
					<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($article)){{$article->meta_description}} @endif" id="meta_description">
				</div>

				<div class="form-group">
					<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($article)) {{$article->body}}  @endif</textarea>
				</div>

				<div class="form-group">
					<label for="show">Hol jelenjen meg?</label>
					<select name="show">
						@foreach($show_options as $key => $val)
							<option value="{{ $key }}"  @if(isset($article) && $key == $article->show) selected @endif>{{ $val }}</option>
						@endforeach
					</select>
					<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
					<div id="public_info" class="collapse info">
						Itt azt tudod beállítani, hogy csak a profilodnál látszódjanak az írásaid, vagy az Írások menüpont alatt is.
						Ugyanakkor az írásod nyilvános, így az interneten is megoszthatod és a keresőmotorok (pl. a Google) is megtalálhatják.
						Lehet az írásod kevésbé közérdekű, inkább személyes blogba illő, ez esetben "csak a profilomnál" megjelenítést állítsd be.
					</div>
				</div>


				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

