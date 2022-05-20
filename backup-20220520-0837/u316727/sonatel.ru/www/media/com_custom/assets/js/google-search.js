
	SiteApp.jQuery(window).load(function() {
		var $ = SiteApp.jQuery;

		$('#search-btn').click(function() {
			$('#search').submit();
			return false;
		});

		// on search page - make google search request
		if ($('.searchResults').length > 0) {
			var searchWord = $.trim($('#mod_search_searchword').val());
			if (searchWord !== '') {
				$('.gsc-input').val(searchWord);
				$('.gsc-search-button').click();
			}
		}
	});
