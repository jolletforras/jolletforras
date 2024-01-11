        @csrf

		<div class="form-group">
			<label for="title">Cím*:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="80" value="@if(isset($commendation)){{old('title',$commendation->title)}}@else{{old('title')}}@endif" id="title">
		</div>

		<div class="form-group">
			<label for="body">Miért ajánlod? Hogyan szolgálja minden ember jóllétét, az új világ megteremtődését?*</label>
			<textarea class="form-control" required="required" rows="10" name="body" cols="50">@if(isset($commendation)){{old('body',$commendation->body)}}@else{{old('body')}}@endif</textarea>
		</div>

		<div class="form-group">
			<label for="url">Itt add meg, ha van hozzá hivatkozás*:</label>
			<input class="form-control" required="required" name="url" type="text" maxlength="255" value="@if(isset($commendation)){{old('url',$commendation->url)}}@else{{old('url')}}@endif" id="url">
		</div>

    	<div class="form-group">
			<input name="active" type="checkbox" value="1" @if(isset($commendation) && $commendation->active) checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Aktív</span>
			<a href="#active_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="active_info" class="collapse info">Ha aktív és engedélyezve van, láthatóvá válik az oldalon.</div>
		</div>

		<div class="form-group">
			<input name="public" type="checkbox" value="1" @if(isset($commendation) && $commendation->public) checked @endif>
			<span style="padding-top: 10px; font-size: 18px;">Nyilvános</span>
			<a href="#public_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a></label>
			<div id="public_info" class="collapse info">Ha nyilvános az ajánló, akkor nem regisztráltak számára is láthatóvá válik.</div>
		</div>

		@if(Auth::user()->admin)
			<div class="form-group">
				<input name="approved" type="checkbox" value="1" @if(isset($commendation) && $commendation->approved) checked @endif>
				<span style="padding-top: 10px; font-size: 18px;">Engedélyez</span>
			</div>
		@endif

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

	</div>
</div>		
		
