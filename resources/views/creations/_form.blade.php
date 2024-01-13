        @csrf

		<div class="form-group">
			<label for="title">Cím*:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="80" value="@if(isset($creation)){{old('title',$creation->title)}}@else{{old('title')}}@endif" id="title">
		</div>

		<div class="form-group">
			<label for="body">Miről szól az alkotásod és miért osztod meg itt?*</label>
			<textarea class="form-control" required="required" rows="10" name="body" cols="50">@if(isset($creation)){{old('body',$creation->body)}}@else{{old('body')}}@endif</textarea>
		</div>

        <div class="form-group">
          <input type="radio" id="url" name="source" value="url" onchange="source_change('url')" @if(!isset($creation) || isset($creation) && !empty($creation->url))checked @endif>
          <label for="url">Alkotásomról hivatkozást adok meg</label><br>
          <input type="radio" id="image" name="source" value="image" onchange="source_change('image')" @if(!isset($creation) || isset($creation) && $creation->has_image)checked @endif>
          <label for="image">Képet töltök fel ({{Auth()->user()->nr_creation_image}}/5)
                <a href="#image_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label><br>
          <div id="image_info" class="collapse info">Összesen 5 alkotás esetében van lehetőség kép feltöltésére</div>
        </div>

		<div class="form-group" id="url_block">
			<label for="url">Itt add meg a hivatkozást, ahol elérhető az alkotásod*:</label>
			<input class="form-control" name="url" type="text" maxlength="255" value="@if(!empty($creation->url)){{old('url',$creation->url)}}@else{{old('url')}}@endif" id="url">
		</div>

        <div class="form-group" id="image_block">
            <label for="url">Itt add meg a képet az alkotásodról*:</label>
            <p>Fájlméret max. 2MB, megengedett formátum: .jpg, .png, .gif</p>
            <input id="upload_image" class="form-control" name="image" type="file">
        </div>

    	<div class="form-group">
			<input name="active" type="checkbox" value="1" @if(isset($creation) && $creation->active) checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Aktív</span>
			<a href="#active_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="active_info" class="collapse info">Ha aktív, akkor láthatóvá válik az oldalon.</div>
		</div>

		<div class="form-group">
			<input name="public" type="checkbox" value="1" @if(isset($creation) && $creation->public) checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha nyilvános az alkotás, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

