        @csrf

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($commendation)) {{$commendation->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="body">Miért ajánlod? Hogyan szolgálja minden ember jóllétét, az új világ megteremtődését?</label>
			<textarea class="form-control" required="required" rows="10" name="body" cols="50">@if(isset($commendation)) {{$commendation->body}} @endif</textarea>
		</div>


		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

	</div>
</div>		
		
