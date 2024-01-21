@extends('layouts.app')
@section('datepicker-css')
	<link href="{{ url('/') }}/css/datepicker.css" rel="stylesheet">
@endsection

@section('content')
	@include('partials.tinymce_js')
	<h2>Esemény felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box" id="create_events_info" style="font-size: 18px">
				Ha eseményt szeretnél közzétenni, az legyen saját esemény, tehát ne más, külső eseményeket ajánlj.
				Mielőtt egy eseményt meghirdetsz, kérlek, gondold végig, hogy az esemény hogyan szolgálja a társadalmi jóllétet, valamint hogy az esemény programja és jellemzői összhangban legyenek a <a href="{{ url('/') }}/kozossegimegallapodas " target="_blank">Közösségi megállapodásban</a> foglaltakkal.
				Az esemény leírásánál szerepeljenek a következők:
				<ul>
					<li>az esemény címe</li>
					<li>a szervező személy vagy csoport neve</li>
					<li>az esemény rövid leírása: mi a téma, a várható program, kik az előadók</li>
					<li>kiknek ajánlod</li>
					<li>mikor és hol lesz, milyen időtartamra terveztek</li>
					<li>hogyan lehet részt venni az eseményen.</li>
					<li>Ha az eseményre regisztrálni kell, add meg a regisztrációs oldal linkjét vagy azt a kapcsolati pontot, ahol jelentkezhetnek azok, akik szeretnének részt venni.</li>
					<li>Ha az eseménynek van részvételi díja vagy elfogadtok adományt, felajánlást, azt is írd meg pontosan.</li>
				</ul>
				@if(isset($group))Az eseményről minden csoporttag kap emailben értesítést.<br/>@endif
			</div>
		</div>
	</div>
	<div class="panel panel-default">
        <div class="panel-body">

			<form method="POST" action="{{url('esemeny')}}/uj" accept-charset="UTF-8">

				@csrf

				<?php $group_id = isset($group) ? $group->id : 0; ?>
				@include('events._form')

			</form>

       </div>
	</div>	
@stop

@include('events._datepicker_footer')