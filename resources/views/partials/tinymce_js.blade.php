    <!-- <script type="text/javascript" src="{{ url('/') }}/js/tinymce/tinymce.min.js"></script> -->
	<script src="https://cdn.tiny.cloud/1/4wpxi6263f6msfr9ig9wlijuaoxktuem219meicnzy4g11e3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
		tinymce.init({
			entity_encoding : "raw",
			selector: 'textarea',
			relative_urls : false,
			remove_script_host : false,
			image_class_list: [
				{title: 'img-responsive', value: 'img-responsive'},
			],
			height: 500,
			setup: function (editor) {
				editor.on('init change', function () {
					editor.save();
				});
			},
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste imagetools"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",

			images_upload_handler: function (blobInfo, success, failure) {
				var image_size = Math.floor(blobInfo.blob().size / 1024);  // image size in kbytes
				var max_size   = 3072;                // max size in kbytes
				if( image_size  > max_size ){
					failure('A kép ('+ image_size  + ' KB) túl nagy, a maximális képméret: ' + max_size + ' KB');
					return;
				}else{
					var xhr, formData;
					xhr = new XMLHttpRequest();
					xhr.withCredentials = false;
					xhr.open('POST', '/kepfeltoltes');
					var token = '{{ csrf_token() }}';
					xhr.setRequestHeader("X-CSRF-Token", token);
					xhr.onload = function () {
						var json;
						if (xhr.status != 200) {
							failure('HTTP Error: ' + xhr.status);
							return;
						}
						json = JSON.parse(xhr.responseText);

						if (!json || typeof json.location != 'string') {
							failure('Invalid JSON: ' + xhr.responseText);
							return;
						}
						success(json.location);
					};
					formData = new FormData();
					formData.append('file', blobInfo.blob(), blobInfo.filename());
					xhr.send(formData);
				}
			}
		});
	</script>