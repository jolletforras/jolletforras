@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Írás felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="article_info" style="font-size: 18px">
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
	<div class="panel panel-default">
        <div class="panel-body">

			<form method="POST" action="{{url('iras')}}/uj" accept-charset="UTF-8">
				@include('articles._form')
			</form>
       </div>
	</div>	
@stop