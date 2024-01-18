@extends('layouts.app')
@section('description', 'Társak a térképen a Társadalmi Jóllét Portálon. A térkép használatához belépés szükséges. Az alábbi kép a 2023.11.24.-ei állapotot mutatja. ')
@section('url', 'https://tarsadalmijollet.hu/terkep')
@section('canonical')<link rel="canonical" href="https://tarsadalmijollet.hu/terkep" /> @endsection
@section('image'){{ url('/images/terkep.jpg')}}@endsection

@section('content')
	@if (!Auth::check())
		<h2>Térkép</h2>
		<div class="inner_box">
			A térkép használatához belépés szükséges. Az alábbi kép a 2023.11.24.-ei állapotot mutatja.
		</div>
		<img width="100%" src="{{ url('/') }}/images/terkep.jpg?4" alt="térkép">
	@else
		@push('styles')
<link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
		@endpush
		<div class="row">
			<div class="col-sm-2">
				<select id="map_type" class="form-control" name="map_type">
					<option value="tarsak" @if($map_type=="tarsak") selected="selected" @endif>társak</option>
					<option value="kezdemenyezesek" @if($map_type=="kezdemenyezesek") selected="selected" @endif>kezdeményezések</option>
					<option value="csoportok" @if($map_type=="csoportok") selected="selected" @endif>csoportok</option>
				</select>
			</div>
			@if(isset($tags))
			<div class="col-sm-3" style="padding-top:3px;">
				<select id="tag" name="tag" class="form-control">
					@foreach($tags as $key => $val)
						<option value="{{ $key }}">{{ $val }}</option>
					@endforeach
				</select>
			</div>
			@endif
		</div>
		<div id="map" style="width: 100%; height: 88vh;"></div>

		<script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
		<script src='https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js'></script>
		<script>
			let map, markers = [];
			/* ----------------------------- Initialize Map ----------------------------- */
			function initMap() {
				map = L.map('map', {
					center: {
						lat: 47.15,
						lng: 19.4,
					},
					zoom: 8
				});

				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '© OpenStreetMap'
				}).addTo(map);

				map.on('click', mapClicked);
				initMarkers();
			}
			initMap();

			/* --------------------------- Initialize Markers --------------------------- */
			function initMarkers() {
				const initialMarkers = <?php echo json_encode($initialMarkers); ?>;

				for (let index = 0; index < initialMarkers.length; index++) {

					const data = initialMarkers[index];
					const marker = generateMarker(data, index);
					marker.addTo(map).bindPopup(`<b>${data.name}</b>`);
					//map.panTo(data.position);
					markers.push(marker)
				}
			}

			function generateMarker(data, index) {
				return L.marker(data.position, {})
				.on('click', (event) => markerClicked(event, index));
			}

			/* ------------------------- Handle Map Click Event ------------------------- */
			function mapClicked($event) {
				console.log(map);
				console.log($event.latlng.lat, $event.latlng.lng);
			}

			/* ------------------------ Handle Marker Click Event ----------------------- */
			function markerClicked($event, index) {
				console.log(map);
				console.log($event.latlng.lat, $event.latlng.lng);
			}

			/* ----------------------- Handle Marker DragEnd Event ---------------------- */
			function markerDragEnd($event, index) {
				console.log(map);
				console.log($event.target.getLatLng());
			}

		</script>


	@endif
@endsection

@section('body_header')
@endsection

@section('footer')
	<script>
		$("#map_type").change(function () {
			var map_type= $("#map_type").val();
			location.href="{{ url('terkep')}}/"+map_type;
		});

		@if (isset($tags))
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
				var map_type= $("#map_type").val();
				location.href="{{ url('terkep')}}/"+map_type+"/cimke/"+id+"/"+tags[id];
			});
		@endif
	</script>
@endsection
