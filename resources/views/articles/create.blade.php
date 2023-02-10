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

				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" maxlength="60" value="" id="title">
				</div>

				<div class="form-group">
					<label for="meta_description">Meta leírás:</label>
					<input class="form-control" required="required" name="meta_description" type="text" maxlength="160" id="meta_description">
				</div>

				<div class="form-group">
					<textarea class="form-control" required="required" rows="20" name="body" cols="50"></textarea>
				</div>

				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>
       </div>
	</div>	
@stop