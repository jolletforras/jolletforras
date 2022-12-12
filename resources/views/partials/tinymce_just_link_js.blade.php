    <!-- <script type="text/javascript" src="{{ url('/') }}/js/tinymce/tinymce.min.js"></script> -->
	<script src="https://cdn.tiny.cloud/1/4wpxi6263f6msfr9ig9wlijuaoxktuem219meicnzy4g11e3/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
		tinymce.init({
			entity_encoding : "raw",
			selector: 'textarea',
			menubar:false,
			relative_urls : false,
			remove_script_host : false,
			setup: function (editor) {
				editor.on('init change', function () {
					editor.save();
				});
			},
			plugins: [
				"advlist autolink lists link charmap print preview anchor",
				"code fullscreen",
				"contextmenu paste"
			],
			toolbar: "undo redo | link",
		});
	</script>