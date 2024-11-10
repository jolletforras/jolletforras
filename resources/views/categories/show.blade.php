@extends('layouts.app')

@section('content')
	<div class="inner_box narrow-page" style="margin-top:6px;">
		<h2>{{ $category->title }}</h2>
		@if (Auth::user()->id==$category->user->id)
			<a href="{{url('iras-csoportok')}}/{{$category->id}}/{{$category->slug}}/modosit" type="submit" class="btn btn-default"><i class="fa fa-edit" aria-hidden="true"> </i>Módosít</a>
			<a href="{{url('iras-csoportok')}}/{{$category->id}}/{{$category->slug}}/kepfeltoltes" type="submit" class="btn btn-default">Képfeltöltés</a>
		@endif
		@if(file_exists(public_path('images/categories/'.$category->id.'.jpg')))
			<p style="text-align: center;"><img src="{{ url('/images/categories') }}/{{ $category->id}}.jpg?{{$category->photo_counter}}" style="max-width: 50%;"></p>
		@endif
		{!! $category->body !!}
		<hr>
		<div class="row">
            @if($type=='article')
                <?php $articles=$list ?>
			    @include('articles._list')
            @endif
		</div>
	</div>
@endsection
