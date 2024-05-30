@extends('layouts.app')
@section('description', 'Társak a Jóllét Forrás oldalon. Nyilvános bemutatkozások. A további bemutatkozásokat belépés vagy regisztráció után éred el. Csatlakozz hozzánk, várunk!')
@section('url', 'https://jolletforras.hu/tarsak')
@section('canonical')<link rel="canonical" href="https://jolletforras.hu/tarsak" />
@endsection

@section('content')
<div class="profile-list">
	<div class="header narrow-page">
		<div class="row">
			@if (Auth::check())
			<div class="col-sm-2">
				<a href="{{url('meghivo')}}/uj" type="submit" class="btn btn-default">Meghívó küldése</a><a href="#user_filter" data-toggle="collapse" id="user_filter_icon"><i class="fa fa-search" aria-hidden="true" style="margin-left: 4px;"></i></a>
			</div>
			<div class="col-sm-10" style="padding-top:3px;">
				<div class="collapse" id="user_filter">
					<select id="user_select" name="user_select" class="form-control" style="width:220px;" onchange="NameFilter();">
						@foreach($user_names as $key => $val)
							<option value="{{ $key }}"@if(isset($user_id) && $key==$user_id) selected @endif>{{ $val }}</option>
						@endforeach
					</select>
					<select id="skill_tag" name="skill_tag" class="form-control" style="width:220px;">
						@foreach($skill_tags as $key => $val)
							<option value="{{ $key }}"@if(isset($skill_tag_id) && $key==$skill_tag_id) selected @endif>{{ $val }}</option>
						@endforeach
					</select>
					<select id="interest_tag" name="interest_tag" class="form-control" style="width:220px;">
							@foreach($interest_tags as $key => $val)
								<option value="{{ $key }}"@if(isset($interest_tag_id) && $key==$interest_tag_id) selected @endif>{{ $val }}</option>
							@endforeach
					</select>
					<select id="city" onchange="CityFilter();" name="city">
						<option value="-" selected="selected">Minden település</option>
						@foreach(constx('CITY') as $key => $val)
							<option value="{{ $key }}">{{ $val }}</option>
						@endforeach
					</select>
					<span id="district_block"  style="display: none;">
						<select id="district" onchange="DistrictFilter();" name="district">
							<option value="-" selected="selected">Minden kerület</option>
							@foreach(constx('DISTRICT') as $key => $val)
								<option value="{{ $key }}">{{ $val }}</option>
							@endforeach
						</select>
					</span>
				</div>
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
		<hr style="margin-top: 10px;margin-bottom: 0px;">
	</div>

	@include('profiles.partials.members_tabs')

</div>
@endsection

@section('footer')
	<script>
		$('#user_select').select2({
			placeholder: 'Név szerint',
		});

		$('#city').select2({
			placeholder: 'Település szerint',
		});

		$('#district').select2({
			placeholder: 'Kerület szerint',
		});

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

		$('#interest_tag').select2({
			placeholder: 'Keresés címke szerint',
			interest_tags: false
		});

		var interest_tags = {
		@foreach ($interest_tags_slug as $id => $slug)
		{{$id}}:"{{$slug}}",
		@endforeach
		};


		$("#user_select").change(function () {
			var id= $("#user_select").val();
			if(id!=0) {
				location.href="{{ url('profil')}}/"+id;
			}
		});


		$("#interest_tag").change(function () {
			var id= $("#interest_tag").val();
			if(id==0) {
				location.href="{{ url('tarsak')}}";
			}
			else {
				location.href="{{ url('tagok')}}/erdeklodes/"+id+"/"+interest_tags[id];
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
				//document.getElementById("district_block").style.visibility = "visible";
				$("#district_block").show();
			}
			else {
				//document.getElementById("district_block").style.visibility = "hidden";
				$("#district_block").hide();
			}

			if ($("#skill_tag").val()==0 && $("#interest_tag").val()==0) {
				Filter(city, district);
			}
			else if($("#skill_tag").val()!=0) {
				SkillTagFilter(city, district);
			}
			else {
				InterestTagFilter(city, district);
			}
		}

		function DistrictFilter() {
			var city = CityName();
			var district=document.getElementById("district").selectedIndex;

			if (district==0) district="";

			if ($("#skill_tag").val()==0 && $("#interest_tag").val()==0) {
				Filter(city, district);
			}
			else if($("#skill_tag").val()!=0) {
				SkillTagFilter(city, district);
			}
			else {
				InterestTagFilter(city, district);
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
					$('#tab1').html(data.html);
					$('#count').html(data.count);
					$("#myTab").find('li:eq(1)').removeClass('active');
					$("#myTab").find('li:eq(0)').addClass('active');
				}
			});
		}

		function SkillTagFilter(city, district) {
			var tag_id = $("#skill_tag").val();

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{ url('skill/filter') }}',
				data: {
					_token: CSRF_TOKEN,
					city: city,
					district: district,
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

		function InterestTagFilter(city, district) {
			var tag_id = $("#interest_tag").val();

			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				type: "POST",
				url: '{{ url('interest/filter') }}',
				data: {
					_token: CSRF_TOKEN,
					city: city,
					district: district,
					interest_id: tag_id,
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