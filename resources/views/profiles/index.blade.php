@extends('layouts.app')
@section('description', 'Társak a Társadalmi Jóllét Portálon. Nyilvános bemutatkozások. A további bemutatkozásokat belépés vagy regisztráció után éred el. Csatlakozz hozzánk, várunk!')
@section('url', 'https://tarsadalmijollet.hu/tarsak')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/tarsak" />
@endsection

@section('content')

	<div class="header">
		<div class="row">
			@if (Auth::check())
			<div class="col-sm-3">
				<a href="{{url('meghivo')}}/uj" type="submit" class="btn btn-default">Meghívó küldése</a>
			</div>
			<div class="col-sm-3" style="padding-top:3px;">
				<select id="tag" name="tag" class="form-control">
					@foreach($tags as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-sm-6" style="padding-top:3px;">
				<select id="city" onchange="CityFilter();" name="city">
					<option value="" selected="selected">Minden település</option>
					@foreach(constx('CITY') as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
				<select id="district" onchange="DistrictFilter();" name="district" style="visibility: hidden;">
					<option value="" selected="selected">Minden kerület</option>
					@foreach(constx('DISTRICT') as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
				&Sigma;: <span id="count">{{count($users)}}</span>
			</div>
			@else
				<div class="col-sm-12"><h2>Nyilvánosan elérhető bemutatkozások</h2></div>
			@endif
		</div>
	</div>

	<div id="result">
		@include('profiles.partials.members_tabs')
	</div>
@endsection

@section('footer')
	<script>
		$('#tag').select2({
			placeholder: 'Keresés címke szerint',
			tags: false
		});

		var tags = {
		@foreach ($tags_slug as $id => $slug)
			{{$id}}:"{{$slug}}",
		@endforeach
		};


		$("#tag").change(function () {
			var id= $("#tag").val();
			location.href="{{ url('tagok')}}/ertes/"+id+"/"+tags[id];
		});

		function CityName() {
			select_city=document.getElementById("city");
			var x = select_city.selectedIndex;
			var y = select_city.options;
			return y[x].value;
		}

		function CityFilter() {
			var city = CityName();

			var district="";

			if(city=='Budapest') {
				document.getElementById("district").style.visibility = "visible";
			}
			else {
				document.getElementById("district").style.visibility = "hidden";
			}

			if ($("#tag").val()=="") {
				Filter(city, district);
			}
			else {
				TagFilter(city, district);
			}
		}

		function DistrictFilter() {
			var city = CityName();
			var district=document.getElementById("district").selectedIndex;

			if (district==0) district="";
			if ($("#tag").val()=="") {
				Filter(city, district);
			}
			else {
				TagFilter(city, district);
			}
		}

		function Filter(city, district) {
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: '{{ url('user/filter') }}',
				data: {
					_token: CSRF_TOKEN,
					city: city,
					district: district,
				},
				success: function(data) {
					$('#result').html(data.html);
					$('#count').html(data.count);
					$("#myTab").find('li:eq(1)').removeClass('active');
					$("#myTab").find('li:eq(0)').addClass('active');
				}
			});
		}

		function TagFilter(city, district) {
			var tag_id = $("#tag").val();

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{ url('skill/filter') }}',
				data: {
					_token: CSRF_TOKEN,
					city: city,
					skill_id: tag_id,
				},
				success: function (data) {
					$('#result').html(data.html);
					$('#count').html(data.count);
					$("#myTab").find('li:eq(1)').removeClass('active');
					$("#myTab").find('li:eq(0)').addClass('active');
				}
			});
		}
	</script>
@endsection