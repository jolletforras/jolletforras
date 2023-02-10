			<script>
				$('#skill_tag_list').select2({
					placeholder: 'Válasz egy címkét vagy vegyél fel ha nincs olyan',
					tags: true,
					maximumSelectionLength: 6,
					language: {
						maximumSelected: function (e) {
							return "Legfeljebb " + e.maximum + " címkét választhatsz";
						}
					}
				});

				$('#interest_tag_list').select2({
					placeholder: 'Válasz egy címkét vagy vegyél fel ha nincs olyan',
					tags: true,
					maximumSelectionLength: 6,
					language: {
						maximumSelected: function (e) {
							return "Legfeljebb " + e.maximum + " címkét választhatsz";
						}
					}
				});
			</script>