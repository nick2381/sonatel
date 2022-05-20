
	SiteApp.CustomTypes.order = function() {
		var $ = SiteApp.jQuery;

		$('#jform_attribs_attr_order_sum')
				.after('<input type="button" class="bСonfirmOrder" value="Отправить подтверджение">');

		$('.bСonfirmOrder').click(function() {
			var currentId = $('#jform_id').val();
			$.post('index.php?option=com_custom&controller=api&task=send_confirmation', {
				'id': currentId
			},
			function(data) {
				if (data == '1') {
					alert('Подтверждение отправлено.');
				}
			});
		});

	};
