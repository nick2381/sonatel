
	SiteApp.CustomTypes = {};

	SiteApp.CustomTypesSubMenu = '\
		<!--li class="customtype"><a href="#10">*Страницы</a></li>\n\
		<li class="customtype"><a href="#12">*Новости</a></li>\n\
		<li class="customtype"><a href="#14">*Продукция</a></li>-->\n\
	';

	SiteApp.initCustomTypes = function($) {
		var src = $('#jform_catid');
		if ($(src).length === 0) {
			return;
		}

		var initCustomFieldsByType = function() {
			var categoryId = $(src).val();

			$.getJSON('index.php?option=com_custom&task=customtype'
					+ '&id=' + categoryId, function(data) {

				// hide attributes fields for not current category
				$('form input,select,textarea').each(function(i, n) {
					var name = $(n).attr('name');
					if (!name) {
						return;
					}
					if (name.indexOf('jform[attribs][attr_') != 0 || 
							name.indexOf('jform[attribs][attr_all_') == 0) {
						return;
					}

					if (data == '' || (data != '' && name.indexOf('jform[attribs][attr_' + data) != 0)) {
						$(n).attr('disabled', 'disabled');
						$(n).closest('li').hide();
					}
				});

				// init for current category
				if (data in SiteApp.CustomTypes) {
					SiteApp.CustomTypes[data]();
				}
			});
		};

		initCustomFieldsByType();
	};

	SiteApp.initCustomTypesMenu = function($) {
		var url = location.href;
		if ((url.indexOf('option=com_content') > 0 && url.indexOf('view=articles') > 0) ||
				(url.indexOf('option=com_content') > 0 && url.indexOf('view=') < 0)) {
			$('#submenu').append(this.CustomTypesSubMenu);

			$('li.customtype a').click(function() {
				var id = $(this).attr('href').substr(1);
				$('select[name="filter_category_id"]').val(id);
				$('#adminForm').submit();
				return false;
			});
		}
	};

	SiteApp.jQuery(function($) {		
		SiteApp.initCustomTypes($);
		SiteApp.initCustomTypesMenu($);
	});