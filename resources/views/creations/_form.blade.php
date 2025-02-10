        @csrf

		<div class="form-group">
			<label for="title">Cím*</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="80" value="@if(isset($creation)){{old('title',$creation->title)}}@else{{old('title')}}@endif" id="title">
		</div>

		<div class="form-group">
			<label for="body">Miről szól az alkotásod és miért osztod meg itt?*</label>
			<textarea class="form-control" required="required" rows="10" name="body" cols="50">@if(isset($creation)){{old('body',$creation->body)}}@else{{old('body')}}@endif</textarea>
		</div>

		<?php
			$max_nr_image = 10;
			$nr_creation_image=Auth()->user()->nr_creation_image;
			$new_creation = !isset($creation);
			$url_checked =  $new_creation || !empty($creation->url); //ha új alkotás, vagy van megadva hivatkozás
			$no_more_image = $nr_creation_image>=$max_nr_image && ($new_creation || $url_checked); //nem lehet képet feltölteni, ha már meg volt a 10 és új alkotás, vagy hivatkozás volt korább
		?>
		<div class="form-group">
			<label for="url">Ha az alkotásod elérhető egy weboldalon, akkor itt adhatod meg annak hivatkozását
				<a href="#url_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<p id="url_info" class="collapse info">Ha a weboldalon az alkotásodnál kép is van, akkor azt nagy valószínűséggel itt is megfogja jeleníteni, de olyan esetekben mint pl. a facebook nem jeleníti meg a képet. Amennyiben nem jeleníti meg, akkor alább képet tudsz feltöltenni. </p>

			<input class="form-control" name="url" type="text" maxlength="255" value="@if(!empty($creation->url)){{old('url',$creation->url)}}@else{{old('url')}}@endif" id="url">
		</div>

		@if(!$no_more_image)
        <div class="form-group">
            <label for="url">Itt adhatsz meg képet az alkotásodról ({{$nr_creation_image}}/{{$max_nr_image}})
				<a href="#image_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<p id="image_info" class="collapse info">Összesen {{$max_nr_image}} alkotás esetében van lehetőség kép feltöltésére.</p>
			<p>Amennyiben a hivatkozás megadása mellett képet is feltöltesz, a hivatkozás előképe a kép lesz.</p>
            <p>Fájlméret max. 2MB, megengedett formátum: .jpg, .png, .gif</p>
            <input id="upload_image" class="form-control" name="image" type="file">
			@if(isset($creation) && !empty($creation->url) && $creation->has_image)
				<p style="margin-top: 6px;">
					<input name="delete_image" type="checkbox" value="1"> csak az aktuális kép törlése <img src="{{ url('/images/creations') }}/{{ $creation->slug}}.jpg?{{$creation->photo_counter}}" style="max-width: 10%;max-height: 50px;">
				</p>
			@endif
        </div>
		@else
			<div class="alert alert-info">
				Eddig {{$max_nr_image}} képes alkotásod van. Amennyiben képet szeretnél feltölteni és nem csak hivatkozást megadni, írj ez ügyben a jolletforras@gmail.com e-mail címre.
			</div>
		@endif

		<div class="form-group">
			<label for="iframe_code">Amennyiben fel van töltve valahol az alkotásod hang/videó formában (pl. facebook, google drive), akkor annak a beágyazás kódját itt adhatod meg
				<a href="#iframe_code_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="iframe_code_info" class="collapse info">
				Az adott helyen, ahova a feltöltötted a hangot/videót általában van egy beágyazás menüpont. Arra kattintva lesz elérhető az iframe beágyazás kódja, azt másold be ide.
				<ul>
					<li>Facebook esetén a videó megnyitása után a 3 pöttyös gombra kattintva találod meg az "Beágyazás" menüpontot. </li>
					<li>Google drive esetén külön oldalon kell megnyitnod a hang/videó fájlt, majd a 3 pöttyös gombra kattintva találod meg az "Elem beágyazása ..." menüpontot.</li>
				</ul>
			</div>

			<textarea class="form-control" rows="4" name="iframe_code" cols="50">@if(!empty($creation->iframe_code)){{old('url',$creation->iframe_code)}}@else{{old('iframe_code')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="category_list">Témakör megadása az alkotással kapcsolatban:</label>
			<a href="#category_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<select name="category">
				<option value="0">nincs megadva</option>
				@foreach($categories as $key => $val)
					<option value="{{ $key }}" @if(isset($creation) && $key == $creation->category_id) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
			<div id="category_info" class="collapse info">
				Ha szeretnéd, akkor az alkotásodat valamelyik korábban létrehozott témakörbe sorolhatod. Amennyiben még nincs létrehozva témakör, az alkotásod elmentése után létrehozhatod, majd az alkotásod módosításánál már beállíthatod a felvett új témakört.
			</div>
		</div>

		@if(isset($creation) && $creation->created_at<date("Y-m-d",strtotime("-2 week")))
			<div class="form-group">
				<label>
					<input name="inactive" type="checkbox" value="1" @if(isset($creation) && !$creation->active) checked @endif>
					<span style="padding-top: 10px; font-size: 18px;">Inaktív</span>
					<a href="#inactive_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
				</label>
				<div id="inactive_info" class="collapse info">A két hétnél régebbi írások inaktívba rakhatóak. Ilyenkor nem jelenik meg az írás a Jóllét Forrás oldalon, csak a hivatkozás ismeretében érhető el.</div>
			</div>
		@endif

		<div class="form-group">
			<label>
				<input name="public" type="checkbox" value="1" @if(isset($creation) && $creation->public) checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
				<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			</label>
			<div id="public_info" class="collapse info">Ha nyilvános az alkotás, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

