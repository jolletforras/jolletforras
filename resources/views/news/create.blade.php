@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Hír felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="create_news_info" style="font-size: 18px">
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
	<div class="panel panel-default">
        <div class="panel-body">
			<form method="POST" action="{{url('hir')}}/uj" accept-charset="UTF-8">

				@csrf

				<div class="form-group">
					<label for="title">Cím:</label>
					<input class="form-control" required="required" name="title" type="text" value="" id="title">
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