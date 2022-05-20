
SiteApp.jQuery(function ($) {

		// init feedback button
	$('.feedback').click(function() {
		$.fancybox({
			type: 'iframe',
			width: 560,
			height: 360,
			autoSize: false,
			fitToView: false,
			href: '/feedback.html?tmpl=component'
		});
	});


    // services menu
		$('.services-nav .categories-module > li > ul').css({"display": "none"});
		$('.services-nav ul ul li.active').parents(".services-nav ul ul").show();
		$('.services-nav li.active').children("ul").show();

		$('.services-nav > ul > li > a').click(function() {
			$(this).next().slideToggle();
			$('.services-nav li a').removeClass('active');
			$(this).addClass('active');
	//		return false;
		});


  });




