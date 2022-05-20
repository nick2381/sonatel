
	SiteApp.CustomTypes.product = function() {
		var $ = SiteApp.jQuery;

		var initPrice = function() {
			var destInput = $('#jform_attribs_attr_product_price');
			if ($(destInput).length === 0) {
				return;
			}

			$(destInput).closest('li').hide();

			var srcInput = $('#jform_attribs_attr_product_price_any');
			var typeSelect = $('#jform_attribs_attr_product_price_type');

			var updateDestInput = function() {
				var v = $(srcInput).val();
				var type = $(typeSelect).val();
				if (type !== '') {
					v = Math.round(SiteApp.ExchangeRates[type] * parseInt(v));
				}

				$(destInput).val(v);
			};

			$(srcInput).change(updateDestInput);
			$(typeSelect).change(updateDestInput);
		};

		var initRelatedProducts = function() {
			if ($('#jform_attribs_attr_product_related').length === 0) {
				return;
			}

			$('#jform_attribs_attr_product_related').hide().closest('li')
					.append(
							'<input type="button" class="bAddRelatedProduct" value="Добавить">')
					.append(
							'<input type="button" class="bEditRelatedProduct" value="Редактировать">');

			$('.bAddRelatedProduct').click(function() {
				if ($('#jform_id').val() === '0') {
					alert('Сначала сохраните материал.');
					return;
				}
				$.fancybox({
					type: 'iframe',
					width: 800,
					height: 500,
					autoSize: false,
					fitToView: false,
					href: 'index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=SiteApp_selectRelatedProduct'
				});
			});

			$('.bEditRelatedProduct').click(function() {
				var currentId = $('#jform_id').val();
				if (currentId === '0') {
					alert('Сначала сохраните материал.');
					return;
				}
				var ids = $('#jform_attribs_attr_product_related').val();
				$.fancybox({
					type: 'iframe',
					width: 800,
					height: 600,
					autoSize: false,
					fitToView: false,
					href: 'index.php?option=com_custom&controller=relatedproducts&tmpl=component'
							+ '&linked_id=' + currentId
				});
			});
		};

		var initBooking = function() {
			$('#jform_attribs_attr_product_type').after(
					'&nbsp;<input type="button" class="bBookingEdit" value="Бронирование">');

			$('.bBookingEdit').click(function() {
				var currentId = $('#jform_id').val();
				if (currentId === '0') {
					alert('Сначала сохраните материал.');
					return;
				}
				$.fancybox({
					type: 'iframe',
					width: 900,
					height: 500,
					autoSize: false,
					fitToView: false,
					href: '/index.php?option=com_custom&controller=booking&tmpl=component&backend=1'
							+ '&id=' + currentId
				});
			});

			/*
			 $.fancybox({
			 type: 'iframe',
			 width: 800,
			 height: 600,
			 autoSize: false,
			 fitToView: false,
			 href: 'index.php?option=com_custom&controller=relatedproducts&tmpl=component'
			 + '&linked_id=' + currentId
			 });
			 */
		};

		initPrice();
		initRelatedProducts();

		initBooking();
		console.log('init ok');
	};

	var SiteApp_selectRelatedProduct = function(id, title, catid, object) {
		var $ = SiteApp.jQuery;
		var currentId = $('#jform_id').val();
		if (id == currentId) {
			return;
		}
		$.post('index.php?option=com_custom&controller=relatedproducts&tmpl=component'
				+ '&task=add'
				+ '&linked_id=' + currentId
				+ '&id=' + id
				+ '&t=' + new Date().getTime(),
				function(data) {
					$.fancybox.close();
				});
	};
