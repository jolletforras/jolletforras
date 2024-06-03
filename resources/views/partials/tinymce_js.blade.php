    <script type="text/javascript" src="{{ url('/') }}/js/tinymce/tinymce.min.js"></script>
	<!-- <script src="https://cdn.tiny.cloud/1/4wpxi6263f6msfr9ig9wlijuaoxktuem219meicnzy4g11e3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
	<script>
		const image_upload = (blobInfo, progress) => new Promise((resolve, reject) => {
			const image_size = Math.floor(blobInfo.blob().size / 1024);  // image size in kbytes
			const max_size   = 3072;                // max size in kbytes
			if( image_size  > max_size ){
				reject('A kép ('+ image_size  + ' KB) túl nagy, a maximális képméret: ' + max_size + ' KB');
				return;
			}else{
				const xhr = new XMLHttpRequest();
				xhr.withCredentials = false;
				xhr.open('POST', '/kepfeltoltes');
				var token = '{{ csrf_token() }}';
				xhr.setRequestHeader("X-CSRF-Token", token);

				xhr.upload.onprogress = (e) => {
					progress(e.loaded / e.total * 100);
				};

				xhr.onload = () => {
					if (xhr.status === 403) {
						reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
						return;
					}

					if (xhr.status < 200 || xhr.status >= 300) {
						reject('HTTP Error: ' + xhr.status);
						return;
					}

					const json = JSON.parse(xhr.responseText);

					if (!json || typeof json.location != 'string') {
						reject('Invalid JSON: ' + xhr.responseText);
						return;
					}

					resolve(json.location);
				};


				const formData = new FormData();
				formData.append('file', blobInfo.blob(), blobInfo.filename());

				xhr.send(formData);
			}
		});

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
			language: 'hu_HU',
			promotion: false,
			plugins: 'link autolink image lists advlist emoticons',
			toolbar: "insertfile undo redo | blocks | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | emoticons",
			images_upload_handler: image_upload
		});
	</script>