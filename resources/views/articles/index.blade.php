@extends('layouts.app')
@section('description', 'Írások a Társadalmi Jóllét Portálon. Milyen legyen az új világunk? Olvasd el a Portál tagjainak írásait, csatlakozz hozzánk és írj Te is saját cikket! Várunk!')
@section('url', 'https://tarsadalmijollet.hu/irasok')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/irasok" />
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<h2>Írások</h2>
		</div>
		@if (Auth::check())
		<div class="col-sm-3 text-right">
			<a href="#article_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 4px;"></i></a><a href="{{url('iras')}}/uj" type="submit" class="btn btn-default">Új Írás</a>
		</div>
		@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box collapse" id="article_info" style="font-size: 18px">
				Ezen az oldalon elsősorban a regisztrált tagok saját írásait várjuk szeretettel. Ha szeretnél itt közzétenni írást, kérlek az alábbiakat fontold meg:<br>
				<ul>
					<li>Az írás hogyan kapcsolódik a társadalmi jólléthez?</li>
					<li>Törekedj a közérthetőségre, olvasható tagolásra és hogy az írás ne legyen túl hosszú. Ajánlott terjedelem: 300-1000 szó.</li>
					<li>Írásod elsősorban saját kutatáson, véleményen, meglátáson alapuljon. Szükség szerint hivatkozhatsz más forrásokra vagy röviden idézhetsz azokból.</li>
					<li>Az írás témája, tartalma és hangvétele összhangban legyen a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodásban</a> foglaltakkal.</li>
				</ul>
			</div>
		</div>
	</div>
  <table class="table">
    <tbody>
	@foreach ($articles as $article)
	  <tr>
      	<td>
			<h3><a href="{{ url('iras',$article->id) }}/{{$article->slug}}">{{ $article->title }}</a></h3>
			@if (Auth::check() && Auth::user()->id==$article->user->id)
				<a href="{{url('iras')}}/{{$article->id}}/{{$article->slug}}/modosit" class="btn btn-default">módosít</a>
			@endif
			<article>
				<div class="body">{!! $article->body !!}</div>
			</article>
		</td>
      </tr>
	@endforeach
    </tbody>
  </table>		
@endsection