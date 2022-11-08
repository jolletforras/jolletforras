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
    @include('errors.list')
	<div class="panel panel-default">
        <div class="panel-body">
			<form method="POST" action="{{url('hir')}}/uj" accept-charset="UTF-8">
                @include('news._form', ['submitButtonText'=>'Mentés'])
			</form>
       </div>
	</div>	
@stop