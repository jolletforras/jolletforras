			<script>
				$('.tag-list').select2({
					placeholder: 'Válasz egy címkét vagy vegyél fel ha nincs olyan',
					tags: true,
					maximumSelectionLength: 6,
					maximumInputLength: 30,
					language: {
						maximumSelected: function (e) {	return "Legfeljebb " + e.maximum + " címkét választhatsz";	},
						noResults: function() {	return "Nincs találat";	},
						inputTooLong: function(e) {	return "Kérlek rövidebb címkét adj meg, mert a címke hossza maximum 30 karakter lehet!";	},
					}
				});
			</script>