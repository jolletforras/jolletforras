@extends('layouts.app')

@section('content')
	@include('partials.tinymce_js')
	<h2>Hír felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="create_news_info" style="font-size: 18px">
				A kezdeményezés kezelőjeként lehetőséged van a kezdeményezés aktuális történéseit, eseményeit egy-egy hírbejegyzésben összefoglalni. A láthatóságnál beállíthatod, hogy azok is értesülhessenek a közösséged legújabb híreiről, akik nem tagjai a portálnak.
			</div>
		</div>
	</div>
    @include('errors.list')
	<div class="panel panel-default">
        <div class="panel-body">
			<form method="POST" action="{{url('kezdemenyezes')}}/hir/uj" accept-charset="UTF-8">
                @include('projectnews._form', ['submitButtonText'=>'Mentés'])
			</form>
       </div>
	</div>	
@stop