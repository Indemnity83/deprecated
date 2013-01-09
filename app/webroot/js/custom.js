/*	
Template Name: Adminium
Description: Modern admin panel interface
Version: 1.3
URL: http://themeforest.net/item/adminium-modern-admin-panel-interface/851824

Author: enstyled
URL: http://themeforest.net/user/enstyled
*/

$(function () {


	// Collapsable navigation	
	$('ul#nav .collapse:not(.open)').parents('li').find('ul').hide();
	
	$('ul#nav .collapse').click(function() {
		if( ! $(this).hasClass('open') ) {
			$(this).parents('li').find('ul').stop(true, true).slideDown('fast');
			$(this).addClass('open');
		} else {
			$(this).parents('li').find('ul').stop(true, true).slideUp('fast');
			$(this).removeClass('open');
		}
		
		return false;
	});
	
	
	
	/* Toggle navigation for mobile browsers */
	$('header').append('<a href="#" id="togglemenu">+</a>');
	
	$('#togglemenu').live('click', function() {
		if( $(this).html() == '+' ) {
			$('aside').slideDown();
			$('#togglemenu').html('-');
		} else {
			$('aside').slideUp();
			$('#togglemenu').html('+');
		}
		
		return false;
	});
	


	// Date picker
	$('input.date_picker').date_input();
	
	
	
	// Color picker
	$('input.color_picker').miniColors();
	
	
	
		
	// Set WYSIWYG editor
	$('.wysiwyg').wysiwyg({
		css: 'css/wysiwyg.css',
		brIE: false
	});
	
	
	
	
	// Fancybox
	$('a.modal').fancybox({
		'overlayOpacity':	0.7,
		'overlayColor'	:	'#000',
		'padding'		:	0
	});
	
	
	
	
	// Check / uncheck all checkboxes
	$('.check_all').click(function() {
		$(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));   
	});
	
	
	

	
	
	// Charts	
	function drawCharts(sourceTable) {
	
		// Types: bar, area, pie, line
		if( sourceTable.attr('rel') ) {
			var chartType = sourceTable.attr('rel');
		} else {
			var chartType = 'line';
		}
		
		var chart_width = sourceTable.parent('div').width() - 50;
				
		if( chartType == 'line' || chartType == 'pie' ) {
		
			sourceTable.hide().visualize({
				type: chartType,
				width: chart_width,
				height: 240,
				colors: ['#3aaef7', '#ec8526', '#68ae00', '#fa504c'],
				lineDots: 'double',
				interaction: true,
				multiHover: 5,
				tooltip: true,
				tooltiphtml: function(data) {
					var html ='';
					for(var i=0; i<data.point.length; i++){
						html += '<p class="chart_tooltip"><strong>'+data.point[i].value+'</strong> '+data.point[i].yLabels[0]+'</p>';
					}	
					return html;
				}
			}).appendTo('.stats_charts').trigger('visualizeRefresh');;
			
		} else {
		
			sourceTable.hide().visualize({
				type: chartType,
				width: chart_width,
				height: 240,
				colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c']
			});
		}
		
		$('.visualize').css('margin-left', '35px');	
	}
	
	
	// Init the charts
	$('table.stats').each(function() {
		drawCharts( $(this) );
	});
	
	
	// Redraw the carts
	$(window).resize(function() {
		$('.visualize').remove();
		
		$('table.stats').each(function() {
			drawCharts( $(this) );
		});
	});
	
	
	
	
	
	
	
	// Sortable tables
	$('table.sortable').tablesorter({
		headers: { 0: { sorter: false}, 5: {sorter: false} },		// Disabled on the 1st and 6th columns
		widgets: ['zebra']
	});
	
	$('table.sortable tr th.header').css('cursor', 'pointer');
		
	
	// Messages
	$('#content .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
	$('#content .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);
		
	$('#content .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});
	
	
	
	
	
	
	// HUD Messages
	function hudMsg(type, message, timeOut) {

		$('.hudmsg').remove();

		if( ! timeOut ) {
			timeOut = 3000;
		}
		
		var timeId = new Date().getTime();
		
		if( type != '' && message != '' ) {
		
			$('<div class="hudmsg '+type+'" id="msg_'+timeId+'"><img src="images/msg_'+type+'.png" alt="" />'+message+'</div>').hide().appendTo('body').fadeIn();
			
			var timer = setTimeout(
				function() {
					$('.hudmsg#msg_'+timeId+'').fadeOut('slow', function() {
						$(this).remove();
					});
				}, timeOut
			);
		}
	}	
	
	
	// Search form
	$('.searchform input.text').each(function() {
	
		var default_value = $(this).val();
		
		$('.searchform').append('<div class="loading"></div>');
		$('.searchform').append('<div class="quickresults"></div>');
				
		$(this).focus(function() {
			$(this).animate({ width: '180px' }, 200);
			
			if( $(this).val() == default_value ) {
				$(this).val('');
			}
		});
						
		
		$(this).keypress(function() {
			if( $(this).val().length > 2 ) {
				
				$('.searchform .loading').fadeIn('fast', function() {
					// AJAX call to remote file
					$.get(
						'test.php',
						{ search_string: $('.searchform input.text').val() },
						function(data) {
							$('.searchform .quickresults').html(data).fadeIn('fast', function() {
								$('.searchform .loading').fadeOut('fast');
							});
						},
						'html'
					);
				});
				
			} else {
				$('.searchform .loading').fadeOut('fast');
				$('.searchform .quickresults').fadeOut('fast');
			}
		});
		
		
		$(this).blur(function() {
			$(this).animate({ width: '100px' }, 200);
			$('.searchform .loading').fadeOut('fast');
			$('header form .quickresults').hide();
			
			if( $(this).val() == '' ) {
				$(this).val(default_value);
			}
		});
	
	});
	
	
	
	
	
	// Custom file input
	$('input.file.styled').each(function() {
		var custom_file_label = $(this).attr('title');
		$(this).wrap('<span class="custom_file" />');
		$(this).parents('.custom_file').append('<input type="button" class="button" value="'+custom_file_label+'" />');
	});
	
	
	$('.custom_file input:button').live('click', function() {
		$(this).parents('span').find('input:file').click();
	});
	
	
	$('.custom_file input.file').change(function() {
		$(this).parents('span').find('em').remove();
		var filename = $(this).val().replace(/^.*\\/, '');
		$(this).parents('span').append('<em>' + filename + '</em>');
	});
	
	
	
	
	
	
	// On/Off switch button
	$('.onoffbtn').each(function() {
		$(this).wrap('<span class="onoff_box" />');
		
		if( $(this).is(':checked') ) {
			$(this).parents('span').addClass('checked');
		}		
	});		
	
	
	$('.onoff_box').live('click', function() {
		
		var onoffSwitch = $(this);
		var chckBox = $(this).find('input.onoffbtn');
				
		if( chckBox.is(':checked') ) {
		
			onoffSwitch.animate({ 'background-position-x': '0' }, 100, function() {
				chckBox.removeAttr('checked');
				$(this).removeClass('checked');
			});
			
		} else {
		
			onoffSwitch.animate({ 'background-position-x': '-40px' }, 100, function() {
				chckBox.attr('checked', 'checked');
				$(this).addClass('checked');
			});
		}
	});
	
	
	
	
	
	// Progress bars animations
	$('.progress_complete').click(function() {
		var totalWidth = $(this).parents('.progress_bar').width();
		$(this).animate({ width: totalWidth }, 600, function() {
			$(this).find('span').html('100%');
		});
	});
	
	
	
	
	
	// Sortable images
	$('ul.imglist').sortable({
		placeholder: 'ui-state-highlight'
	});
	
	
	
	
	// Sliders
	$('.slider').slider({
		min: 0,
		max: 100,
		step: 10,
		value: 20,
		create: function(event, ui) {
			$(this).find('.ui-slider-handle').addClass('tt').attr('title', ($(this).slider('value') + '%'));
		},
		slide: function(event, ui) {
			$(this).find('.ui-state-active.ui-state-active').attr('title', (ui.value + '%'));
		}
	});
	
	
	$('.slider-range').slider({
		range: true,
		min: 0,
		max: 100,
		values: [20, 80],
		step: 10,
		create: function(event, ui) {
			$(this).find('.ui-slider-handle').addClass('tt').each(function() {
				var percent = $(this).parents('.slider-range').slider('values', ($(this).index() - 1)) + '%';
				$(this).attr('title', percent);
			});
		},
		slide: function(event, ui) {
			$(this).find('.ui-slider-handle.ui-state-active').attr('title', (ui.value + '%'));
		}
	});
	
	
	
	
	// Tooltips
	$('.tt').tipsy({
		gravity: 's'
	});
		
	
	
		
	// Image actions menu
	$('ul.imglist li').hover(
		function() { $(this).find('ul').css('display', 'none').stop(true, true).fadeIn('fast').css('display', 'block'); },
		function() { $(this).find('ul').stop(true, true).fadeOut(100); }
	);
	
		
	// Image delete confirmation
	$('ul.imglist .delete a').click(function() {
		if( confirm('Are you sure you want to delete this image?') ) {
		
			// Make AJAX call to delete
						
			$(this).parents('li').fadeOut('slow', function() {
				$(this).remove();
				hudMsg('success', 'Image deleted successfully', 3000);
			});
		}
		return false;
	});
});