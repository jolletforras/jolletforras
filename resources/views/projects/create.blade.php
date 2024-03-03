@extends('layouts.app')

@section('content')
	<h2>Kezdeményezés felvétele</h2>
	<div class="row">
		<div class="col-sm-12">
			<div class="inner_box">
				<b>A kezdeményezés felvételekor a következőkre várjuk a válaszod:</b><br>
				<ul>
					<li>Miért indítod ezt a kezdeményezést?</li>
					<li>Milyen értékeket hordoz vagy támogat a kezdeményezésed?</li>
					<li>Hogyan szolgálja az új világ építését?</li>
					<li>Miről szól a kezdeményezés, mi az elképzelésed, megvalósítási terved?</li>
					<li>Te kezdeményezőként mit tudsz beletenni a megvalósításba?</li>
				</ul>
				<b>További tudnivalók</b><br>
					A kezdeményezés a <a href="{{ url('/') }}/csoport/1/tarsadalmi-jollet-mag" target="_blank">Társadalmi Jóllét Mag</a> jóváhagyása után kerül fel.
					A jóváhagyás szempontjai: a portál <a href="<?php echo e(url('/')); ?>/kozossegimegallapodas " target="_blank">közösségi megállapodásában</a> megfogalmazott küldetéssel és értékrenddel összhangban van-e,
					valamint a feltett kérdésekre választ ad-e. Ha az értékrenddel és küldetéssel nincs összhangban, elutasítjuk a megjelenést.
					Ha a feltett kérdésekre nem ad választ, hiányos, akkor a kiegészítést követően jóváhagyjuk.<br>
				<br>
				<b>A kezdeményezés kezelőjének vállalásai:</b><br>
				<ul>
					<li>A leírást élőn tartja, frissíti. Ha már nem időszerű, akkor törli a kezdeményezést.</li>
					<li>Legalább félévente ír egy rövid hírt arról (kezdeményezés/Hírek fül), hol tart a kezdeményezés.</li>
				</ul>
				Aki nem teljesíti a vállalását, tudomásul veszi, hogy inaktívba kerül a kezdeményezése addig, míg nem pótolja a beszámolót.<br>
			</div>
		</div>
	</div>

	@include('errors.list')
	<div class="panel panel-default">
		<div class="panel-body">

			<form method="POST" action="{{url('kezdemenyezes')}}/uj" accept-charset="UTF-8">

				@csrf

				@include('projects._form')
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Mentés">
				</div>

			</form>

		</div>
	</div>
@stop

@section('footer')
	@include('projects._script')
	@include('partials.add_tag_no_new_script')
@endsection