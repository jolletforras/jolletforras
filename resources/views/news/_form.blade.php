        @csrf

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" value="@if(isset($news)) {{$news->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="meta_description">Meta leírás:</label>
			<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" value="@if(isset($news)) {{$news->meta_description}} @endif" id="meta_description">
		</div>

		<div class="form-group">
			<label for="body">Szöveg:</label>
			<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($news)) {{$news->body}} @endif</textarea>
		</div>

		<div class="form-group">
			<label for="tag_list">Címkék:</label>
			<select id="tag_list" name="tag_list[]" class="form-control" multiple>
				@foreach($tags as $key => $val)
					<option value="{{ $key }}" @if(isset($selected_tags) && in_array($key,$selected_tags)) selected @endif>{{ $val }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" value="{{$submitButtonText}}">
		</div>

		@section('footer')
			@include('partials.add_tag_script')
		@endsection
	</div>
</div>		
		
