<script>
	var tags = {
	@foreach ($tags_slug as $id => $slug)
	{{$id}}:"{{$slug}}",
	@endforeach
	};

	$('#tag').select2({
		placeholder: 'Keresés címke szerint',
        "language": {
            "noResults": function(){
                return "Nincs találat";
            }
        },
		tags: false
	});

	$("#tag").change(function () {
		var id= $("#tag").val();
		location.href="{{ url('/')}}/{{$url}}/cimke/"+id+"/"+tags[id];
	});
</script>