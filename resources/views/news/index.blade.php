@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Hírek</h2><a href="#news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
		</div>
		@if (Auth::check())
		<div class="col-sm-3 text-right">
			<a href="#create_news_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('hir')}}/uj" type="submit" class="btn btn-default">Új hír</a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="news_info" style="font-size: 18px">
				Ezen az oldalon találhatod a Portál fejlesztésével kapcsolatos újdonságok bemutatását, valamint a regisztrált tagok  és csoportok híreit.
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="create_news_info" style="font-size: 18px">
				Ha hírt szeretnél közzétenni, kérlek, törekedj arra, hogy
				<ul>
					<li>lényegretörően és közérthetően fogalmazz</li>
					<li>a hírben szereplő adatok és tények pontosak legyenek</li>
					<li>a hír kapcsolódjon a társadalmi jólléthez</li>
					<li>a hír címe, tartalma és hangvétele összhangban legyen a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodásban</a> foglaltakkal</li>
				</ul>
			</div>
		</div>
	</div>
    <table class="table">
    <tbody>
	@foreach ($news as $nws)
	  <tr>
      	<td>
			<a href="{{ url('profil',$nws->user->id) }}/{{$nws->user->slug}}">{{ $nws->user->name }}</a>,	{{ $nws->updated_at }}
			@if (Auth::check() && (Auth::user()->id==$nws->user->id || Auth::user()->admin))
				<a href="{{url('hir')}}/{{$nws->id}}/modosit">módosít</a>
			@endif
			<article>
				<div class="body">{!!$nws->body !!}</div>
			</article>
		</td>
      </tr>
	@endforeach
    </tbody>
  </table>		
@endsection