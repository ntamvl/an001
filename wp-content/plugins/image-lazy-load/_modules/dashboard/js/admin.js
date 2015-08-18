jQuery(document).ready(function($) {	
	/**
	* Top level tabbed interface. If defined in the view:
	* - tabs are set to display, as JS is enabled
	* - the selected tab's panel is displayed, with all others hidden
	* - clicking a tab will switch which panel is displayed
	*/
	if ($('h2.nav-tab-wrapper.needs-js').length > 0) {
		// Show tabbed bar
		$('h2.nav-tab-wrapper.needs-js').fadeIn('fast', function() {
			$(this).removeClass('needs-js');
		});
		
		// Hide all panels except the active one
		$('#normal-sortables div.panel').hide();
		var activeTab = $('h2.nav-tab-wrapper a.nav-tab-active').attr('href')+'-panel';
		$(activeTab).show();
		
		// Change active panel on tab change
		$('h2.nav-tab-wrapper a').click(function(e) {
			e.preventDefault();
			
			// Deactivate all tabs, hide all panels
			$('h2.nav-tab-wrapper a').removeClass('nav-tab-active');
			$('#normal-sortables div.panel').hide();
			
			// Set clicked tab to active, show panel
			$(this).addClass('nav-tab-active');
			var activeTab = $(this).attr('href')+'-panel';
			$(activeTab).show();
		});
	}
	
	/**
	* Second level tabbed interface. If defined in the view:
	* - tabs are set to display, as JS is enabled
	* - the selected tab's panel is displayed, with all others hidden
	* - clicking a tab will switch which panel is displayed
	*/
	if ($('h3.nav-tab-wrapper.needs-js').length > 0) {
		// Iterate through each sub tab bar
		$('h3.nav-tab-wrapper.needs-js').each(function() {
			// Show tabbed bar
			$(this).fadeIn('fast', function() {
				$(this).removeClass('needs-js');
			});
			
			// Hide all sub panels except the active one
			$('div.sub-panel', $(this).parent()).hide();
			var activeTab = $('a.nav-tab-active', $(this)).attr('href')+'-panel';
			$(activeTab).show();
			
			// Change active panel on tab change
			$('a', $(this)).click(function(e) {
				e.preventDefault();
				
				// Deactivate all tabs, hide all panels
				$('a', $(this).parent()).removeClass('nav-tab-active');
				$('div.sub-panel', $(this).parent().parent()).hide();
				
				// Set clicked tab to active, show panel
				$(this).addClass('nav-tab-active');
				var activeTab = $(this).attr('href')+'-panel';
				$(activeTab).show();
			});
		});
	}
	
	/**
	* Debug
	* When the debug textarea is clicked, select all of the text inside it ready for
	* the user to copy
	*/
	if ($('textarea[name=wpcube-debug]').length > 0) {
		$('textarea[name=wpcube-debug]').focus(function() {
			$(this).select();
			$(this).mouseup(function() {
				// Prevent further mouseup intervention
				$(this).unbind("mouseup");
				return false;
			});	
		});
	}
});