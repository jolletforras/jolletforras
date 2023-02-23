@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Hír felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="create_news_info" style="font-size: 18px">
				Csoport kezelőként lehetőséged van a csoport aktuális történéseit, eseményeit egy hírben összefoglalni. Ha láthatóságnál beállítod hogy azok is értesülhessenek a csoport legújabb fejleményeiről, akik nem tagja a csoportnak.
			</div>
		</div>
	</div>
    @include('errors.list')
	<div class="panel panel-default">
        <div class="panel-body">
			<form method="POST" action="{{url('hir')}}/uj" accept-charset="UTF-8">
                @include('groupnews._form', ['submitButtonText'=>'Mentés'])
			</form>
       </div>
	</div>	
@stop