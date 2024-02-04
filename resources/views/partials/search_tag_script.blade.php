<script>
	var tags = {
	@foreach ($tags_slug as $id => $slug)
	{{$id}}:"{{$slug}}",
	@endforeach
	};

	var placeholder = 'Keresés címke szerint';
    if('{{$url}}'=='hir') {
        placeholder = 'Keresés csoport címke szerint';
    }

	$('#tag').select2({
		placeholder: placeholder,
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