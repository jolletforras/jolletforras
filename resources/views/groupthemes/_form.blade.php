		@csrf

		<input name="group_id" type="hidden" value="{{$group->id}}">

		<div class="form-group">
			<label for="title">Cím:</label>
			<input class="form-control" required="required" name="title" type="text" maxlength="60" value="@if(isset($forum)) {{$forum->title}} @endif" id="title">
		</div>

		<div class="form-group">
			<label for="body">Szöveg:</label>
			<textarea class="form-control" required="required" rows="20" name="body" cols="50">@if(isset($forum)) {{$forum->body}} @endif</textarea>
		</div>

		@if($group->isAdmin())
		<div class="form-group">
			<input name="announcement" type="checkbox" value="1" @if(isset($forum) && $forum->announcement) checked @endif> közlemény
		</div>
		@endif

		<div class="form-group">
			<label for="tag_list">Címkék:</label>
			<select id="tag_list" name="tag_list[]" class="form-control tag-list" multiple>
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
		
