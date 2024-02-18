@extends('layouts.app')
@section('title'){{ $article->title }}@endsection
@section('description'){{ $article->meta_description }}@endsection
@section('url'){{url('iras')}}/{{$article->id}}/{{$article->slug}}@endsection
@section('canonical')<link rel="canonical" href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}" />@endsection
@section('image')@if(!empty($article->image)){{url('/')}}/images/posts/{{$article->image}}@else{{url('/images/tarsadalmijollet.png')}}@endif
@endsection


@section('content')
	<div class="inner_box narrow-page">
		<h2>{{ $article->title }}</h2>
		@if (Auth::check() && Auth::user()->id==$article->user->id)
			<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" type="submit" class="btn btn-default">Módosít</a>
			<button class="btn btn-default" type="button" onclick="delete_article()">Töröl</button>
			<a href="{{ url('profil',Auth::user()->id) }}/{{Auth::user()->slug}}/irasok"><< Írásaim</a>
		@endif
		{!! $article->body !!}
		@include('partials.author', ['author'=>'','obj'=>$article])
		@if(Auth::check())
			@include('articles._tags')
		@endif
		<p><a href="{{url('irasok')}}"><< Írások</a></p>
    </div>
	@if(Auth::check())
		@include('comments._show', ['comments' => $comments] )
	@endif
@endsection

@section('footer')
	@if(Auth::check())
		@include('partials.comment_script', [
			'commentable_type'	=>'Article',
			'commentable_url'	=>'iras/'.$article->id.'/'.$article->slug,
            'commentable_id'	=>$article->id,
            'name'				=>$article->user->name,
            'email'				=>$article->user->email
        ] )

		<script type="text/javascript">
			function delete_article() {
				if (confirm("Biztosan törölni szeretné az írást?") == true) {
					window.location.href = "{{url('iras')}}/{{$article->id}}/{{$article->slug}}/torol";
				}
			}
		</script>
	@endif
@endsection

