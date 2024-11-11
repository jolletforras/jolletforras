	<div class="row narrow-page">
		<div class="col-sm-9">
			<button class="btn btn-default" id="categories_btn" onclick="toogle()" style="display: inline;"><i class="fa fa-angle-double-down" aria-hidden="true"></i> Témakör szerint</button>
		</div>
	</div>
	<div class="row" id="categories">
		@foreach ($categories as $category)
			<div class="col-12 col-sm-6 col-md-4">
				<div class="card">
					<div class="card-header"></div>
					<div class="image-box">
						<div class="image" style="background-image:url('/images/categories/{{ $category->id}}.jpg?{{$category->photo_counter}}');"></div>
					</div>
					<div class="card-body">
						<h3><a href="{{ url('irasok',$category->id) }}/{{$category->slug}}">{{$category->title}}</a></h3>
						<div>{!! $category->body !!}</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	@section('footer')
		<script>
			$('#categories').hide();

			function toogle(){
				if($('#categories').is(":hidden")) {
					$('#categories').show();
				} else {
					$('#categories').hide();
				}
			}
		</script>
	@endsection