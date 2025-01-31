@extends('layouts.app')
@section('description'){{$creation->user->name}} alkotása: {{ $creation->title }}@endsection
@section('url'){{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}" />@endsection
@section('image')@if($creation->has_image){{url('images')}}/creations/{{$creation->slug}}.jpg?{{$creation->photo_counter}}@elseif(!empty($creation->meta_image)){{$creation->meta_image}}@else{{url('/images/jolletforras.png')}}@endif
@endsection

@section('content')
	<div class="inner_box narrow-page">
		<h2>{{ $creation->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$creation->user->id)
			<a href="{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			<button class="btn btn-default" type="button" onclick="delete_creation()">Töröl</button>
			<a href="{{ url('profil',Auth::user()->id) }}/{{Auth::user()->slug}}/alkotasok"> << Alkotásaim</a>
		@endif
		<br>
		@if($creation->has_image && empty($creation->url))
			<p style="text-align: center;"><img src="{{ url('/images/creations') }}/{{ $creation->slug}}.jpg?{{$creation->photo_counter}}" style="max-width: 50%;max-height: 400px;"></p>
		@endif
		{!! nl2br($creation->body) !!}
		@if(!empty($creation->url))
			<div class="inner_box" style="background-color: #fbfbfb">
				<p><a href="{{ $creation->url }}" target="_blank">@if(empty($creation->meta_title)){{ $creation->url}}@else{{ $creation->meta_title }}@endif</a></p>
				@if(!empty($image_url))
				<p><a href="{{ $creation->url }}" target="_blank"><img src="{{$image_url}}" style="max-height: 300px; max-width:100%; display: block; margin-left: auto; margin-right: auto;"></a></p>
				@endif
				@if(!empty($creation->meta_description))
				<p>{{ $creation->meta_description }}</p>
				@endif
			</div>
		@endif
		@include('partials.author', ['author'=>'','obj'=>$creation])
    </div>
	@if(Auth::check())
		@if(Auth::user()->isGroupAdmin())
			<p class="narrow-page"><button class="btn btn-default" type="button" onclick="get_group_admin_block()" id="btn_group_admin_block"><i class="fa fa-angle-double-down" aria-hidden="true"></i>Csoporthoz való hozzáadás/törlés</button></p>
			<div class="inner_box narrow-page" id="group_admin_block" style="display: none;">... hamarosan betölt</div>
		@endif

		@include('comments._show', ['comments' => $comments] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
			'commentable_type'	=>'Creation',
			'commentable_url'	=>'alkotas/'.$creation->id.'/'.$creation->slug,
			'commentable_id'	=>$creation->id,
			'name'				=>$creation->user->name,
			'email'				=>$creation->user->email
		] )

		@include('partials.post_group_script', [
			'post_type'	=>'creation',
			'post_id'	=>$creation->id,
		] )

		<script type="text/javascript">
			function delete_creation() {
				if (confirm("Biztosan törölni szeretné az alkotást?") == true) {
					window.location.href = "{{url('alkotas')}}/{{$creation->id}}/{{$creation->slug}}/torol";
				}
			}
		</script>
	@endif
@endsection
