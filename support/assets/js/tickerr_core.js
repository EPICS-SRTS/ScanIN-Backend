function enable_dropdown(container) {
	$(container).dropdown();
}

function enable_sidebar() {
	$('.navbar-toggle').click(function(evt) {
		if($('.sidebar-left').hasClass('shown')) {
			$('.sidebar-left').animate({'left':'-300px'}, 300).removeClass('shown');
			$('.navbar-toggle').removeClass('active');
		}else{
			$('.sidebar-left').animate({'left':'0px'}, 300).addClass('shown');
			$('.navbar-toggle').addClass('active');
		}
	});
	
	$('ul.navigation > li > a').click(function(evt) {
		var t = $(this);
		
		if($(this).next().hasClass('dropdown') === false)
			return;
		
		evt.preventDefault();
		
		// Is this one open? Just close it
		if($(this).next().is(':visible')) {
			t.next().removeClass('animated');
			t.next().slideUp(300);
			t.children('span.arrow').children('i.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
			return;
		}
		
		// Close all dropdowns
		$('ul.navigation ul.dropdown.animated').removeClass('animated');
		$('ul.navigation ul.dropdown').slideUp(300);
		$('ul.navigation i.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
		
		// Open new
		$(this).next().slideDown(300, function() {
			t.next().addClass('open');
			t.children('span.arrow').children('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
			t.next().addClass('animated');
		});
	});
}