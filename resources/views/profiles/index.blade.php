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
				<select id="skill_tag" name="skill_tag" class="form-control">
					@foreach($skill_tags as $key => $val)
						<option value="{{ $key }}"@if(isset($skill_tag_id) && $key==$skill_tag_id) selected @endif>{{ $val }}</option>
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
				<div class="col-sm-12">
					<h2>Nyilvánosan elérhető bemutatkozások</h2><a href="#public_introduction_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true" style="margin-left: 4px;"></i></a>
					<div class="row">
						<div class="col-sm-12">
							<div class="inner_box collapse" id="public_introduction_info" style="font-size: 18px">
								További bemutatkozások megtekintéséhez lépj be vagy regisztrálj az oldalon!
							</div>
						</div>
					</div>
				</div>

			@endif
		</div>
	</div>

	<div id="result">
		@include('profiles.partials.members_tabs')
	</div>
@endsection

@section('footer')
	<script>
		$('#skill_tag').select2({
			placeholder: 'Keresés címke szerint',
			skill_tags: false
		});

		var skill_tags = {
		@foreach ($skill_tags_slug as $id => $slug)
			{{$id}}:"{{$slug}}",
		@endforeach
		};


		$("#skill_tag").change(function () {
			var id= $("#skill_tag").val();
			if(id==0) {
				location.href="{{ url('tarsak')}}";
			}
			else {
				location.href="{{ url('tagok')}}/ertes/"+id+"/"+skill_tags[id];
			}
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

			if ($("#skill_tag").val()==0) {
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
			if ($("#skill_tag").val()==0) {
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
			var tag_id = $("#skill_tag").val();

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