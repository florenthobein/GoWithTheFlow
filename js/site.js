jQuery(document).ready(function( $ ) {

	////////////////// VARS //////////////////

	var page_width = $(window).width(),
		page_height = $(window).height(),
		old_scroll_top = scroll_top = 0;
	var articles_read = [];
	var index = [];
	var visible_article = null;
	var flag_next_article = false;
	var modif_enabled = $('body').hasClass('editable');
	var map_center_h, map_center_v;
	var current_page = 0;

	////////////////// CHECKS //////////////////

	if (!$('html').hasClass('js') || !$('html').hasClass('backgroundsize') || !$('html').hasClass('opacity'))
		$('#disabler').show().find('.old_browser').show();
	else if (page_width <= 800)
		$('#disabler').show().find('.small_device').show();

	////////////////// CALLS //////////////////

	// Gestion du preloader
	display_loader();
	
	// Background
	$('#background_container, #background_container .transition').css({
		background: $('body').css('background')
	});
	$('#background_container .transition').css('opacity', 0);

	// General
	$('#container, #map_container, #background_container').css({
		width:	page_width,
		height:	page_height
	});
	$('.page').css({
		width:	page_width
	});
	$('.page.next').css({
		top:	page_height,
		minHeight: page_height
	});

	var distance_top = page_height/2 - $('.page.home').height() - $('.page.home .description').height()*2;
	$('.page.home').css({
		paddingTop: distance_top
	}).attr('data-background', $('body').css('background'));
	$('.page.home .stripe1').css({
		height: distance_top - 50,
		marginTop: 0
	});
	$('.page.home .stripe2').css({
		top: distance_top + $('.page.home .description').height()*2 + 50*2.5,
		height: page_height/5
	});
	$('.page.home .button_next').css({
		top: distance_top + $('.page.home .description').height()*2 + 50*2.5 + page_height/5
	});

	// Creation de l'index
	index.push({
		dom: $('.page.home'),
		start: 0,
		end: page_height
	});

	// Chargement des pages
	$.when( get_next_article() ).done( function () {
		$.when( get_next_article() ).done( hide_loader );
	});

	// Map
	$('.map').one('load', function() {
		map_center_h = 10000/2 - 270; // position of the map at lon 0
		map_center_v = 5597/2 - 100; // position of the map at lat 0
		$('#map_container .pointer').css({
			left: map_center_h,
			top: map_center_v
		});
	}).each(function() {
		if(this.complete) $(this).load();
	});

	////////////////// BINDINGS //////////////////

	$(document).scrollTop(0);
	$(document).scroll(scroll_controller);

	if (modif_enabled) {
		$(document).delegate('.block', 'click', function () {
			$('.page').removeClass('current');
			$('.block.selected').removeClass('selected');
			$(this).addClass('selected').parents('.page').addClass('current');
			active_admin();
		});
		$('#adminbar input').keyup(function () {
			var value = parseInt($(this).val(), 10);
			value = isNaN(value) ? 0 : value;
			$($(this).attr('data-target-dom')).css($(this).attr('data-target-css'), value);
		});
	}
	$('#header_handle').click(function () {
		if ($('#header:visible').length == 0) {
			var menu_height = $('#header').show().outerHeight();
			$('#header').hide();
			$(this).animate({ top: menu_height-14 }, 500);
		} else {
			$(this).animate({ top: -14 }, 500);
		}
		$('#header').slideToggle(500);
	})
	$(document).delegate('.next_elt', 'click', function () {
		$(this).slideUp('fast');
		$(this).parents('.content_elt').next('.content_elt').slideDown('fast');
	});
	$(document).delegate('.next_block', 'click', function () {
		$(this).slideUp('fast');
		if ($(this).parents('.block').next().hasClass('block'))
			$(this).parents('.block').next('.block').fadeIn(); // cas du clr
		else
			$(this).parents('.block').next().next('.block').fadeIn();
	});
	$('.btn_display').click(function () {
		if ($('body').hasClass('no-text') && $('body').hasClass('no-map')) {
			$('body').removeClass('no-text').removeClass('no-map');
		} else if ($('body').hasClass('no-text')) {
			$('body').addClass('no-map');
		} else {
			$('body').addClass('no-text');
			if ($('#map_container').css('opacity') == 0)
				$('body').addClass('no-map');
		}
	});
	$('.btn_menu').click(function () {
		$('#menu').fadeToggle('fast');
	});
	$('.btn_previousarticle').click(function () {
		if (isset(index[current_page-1])) {
			var article_height = index[current_page-1].dom.find('.to_shift').height();
			if (article_height + 100 < page_height)
				$(window).scrollTo(index[current_page-1].start - page_height/2 + article_height/2 - 15, {duration: 1000});
			else
				$(window).scrollTo(index[current_page-1].start - page_height/2 + 200, {duration: 1000});
		} else {
			$(window).scrollTo(0, {duration: 1000});
		}
	});
	$('.btn_nextarticle').click(function () {
		if (isset(index[current_page+1])) {
			var article_height = index[current_page+1].dom.find('.to_shift').height();
			if (article_height + 100 < page_height)
				$(window).scrollTo(index[current_page+1].start - page_height/2 + article_height/2 - 15, {duration: 1000});
			else
				$(window).scrollTo(index[current_page+1].start - page_height/2 + 200, {duration: 1000});
		} else {
			$(window).scrollTo($(document).height() - page_height, {duration: 1000, onAfter: function () { current_page = 0; }});
		}
	});

	////////////////// FUNCTIONS //////////////////

	// Functions to handle the loader
	function display_loader () {
		$('#preloader').css({
			width:	page_width,
			height:	page_height
		}).find('> div').css({
			top:	page_height/2 - 100
		}).find('.loadbar div').animate(
			{ width: '100%' },
			1000
		);
	}
	function hide_loader () {
		$('#preloader .loadbar div').stop().css('width', '100%');
		setTimeout( function () { $('#preloader').fadeOut(function () { $(this).remove(); }); } , 500);
	}

	// Retreive the data and populate
	// the DOM
	function get_next_article () {
		flag_next_article = true;
		return get_article().done(function (data) {
			data = data.length ? JSON.parse(data) : false;

			if (!data || isset(data.end)) {
				$('.page.home')
					.clone()
					.appendTo('#container')
					.css({
						top: 2*parseFloat($('.page.home').css('padding-top').replace('px', '')),
						height: page_height,
						boxSizing: 'border-box'
					})
					.find('.stripe2')
						.css('height', page_height-parseFloat($('.page.home .stripe2').css('top').replace('px', '')))
					.next('.button_next')
						.hide();
				if (data.end.length)
					$('.page:last-child .description').show().html(data.end);
				flag_last_article = true;
				return false;
			}

			// Meta infos
			
			$('.page.next').attr('data-id', data.id);
			$('.page.next .title').html(data.title);
			$('.page.next .custom.block').remove();
			if (data.day != '&nbsp;')	$('.page.next .day').html(data.day).show();
				else					$('.page.next .day').hide();
			$('.page.next .month').html(data.month);
			$('.page.next .year').html(data.year);
			if (data.city)				$('.page.next .city').html(data.city).show().next().show();
				else					$('.page.next .city').hide().next().hide();
			if (data.country)			$('.page.next .country').html(data.country).show().next().show();
				else					$('.page.next .country').hide().next().hide();
			if (data.day == '&nbsp;' && data.month == '&nbsp;' && data.year == '&nbsp;' && !data.city && !data.country) {
				$('.page.next').addClass('no-meta');
				$('.page.next .month, .page.next .year').hide();
			} else {
				$('.page.next').removeClass('no-meta');
				$('.page.next .month, .page.next .year').show();
			}
			var custom_bkg_image_credits = data.background_image ? data.background_image.caption : '';
			if (custom_bkg_image_credits.length)	$('.page.next .meta .image').show().find('.credits').html(custom_bkg_image_credits);
				else								$('.page.next .meta .image').hide();

			// Position & dimensions

			var custom_width = parseInt(data.block_width, 10),
				block_width = custom_width ? custom_width : page_width/2;
			$('.page.next .block:not(.custom)').css({
				width: block_width,
				marginLeft: -block_width/2
			});
			$('.page.next .to_shift').css({
				marginLeft: data.horizontal_shift+'px',
				marginTop: data.vertical_shift+'px'
			});

			// Content

			if (typeof data.content == 'string')
				$('.page.next .content').html(data.content);
			else
				parse_content(data.content);

			// Background

			var custom_bkg_image = data.background_image ? data.background_image.url : '';
			var custom_bkg_color = data.background_color;
			var background = {
				backgroundImage: custom_bkg_image.length ? 'url('+custom_bkg_image+')' : 'none',
				backgroundColor: custom_bkg_color.length ? custom_bkg_color : 'none',
			};
			$('.page.next').attr('data-background', background.backgroundImage != 'none' ? background.backgroundImage : background.backgroundColor);
			
			// Map

			var no_map = data.longitude == 0 || data.latitude == 0 || isNaN(data.longitude) || isNaN(data.latitude);
			if (no_map) {
				$('.page.next').attr('data-map', '');
			} else {
				var map_shift_h = isset(data.map_horizontal_shift) ? parseInt(data.map_horizontal_shift) : 0,
					map_shift_v = isset(data.map_horizontal_shift) ? parseInt(data.map_vertical_shift) : 0;
				var map_left = -27.761599452 * data.longitude - map_center_h + page_width/2,
					map_top = 32.19854605 * data.latitude - map_center_v + page_height/2;
				var pointer_left = -map_left + page_width/2,
					pointer_top = -map_top + page_height/2;

				$('.page.next').attr('data-map', map_left+'|'+map_top)
							   .attr('data-map-pointer', pointer_left+'|'+pointer_top)
							   .attr('data-map-shift', map_shift_h+'|'+map_shift_v);
			}

			// Galleries
			$('.page.next .meta .pictures').html('');
			if ($('.page.next .gallery').length) {
				$('.page.next .gallery').clone().appendTo('.page.next .meta .pictures').removeClass('gallery');
				$('.page.next .gallery').remove();
			}

			// Append the article
			var new_page = $('.page.next').clone().removeClass('next').appendTo('#container');

			// Activate the gallery
			if ($('.page:last-child .meta .pictures a').length) {
				$('.page:last-child .meta .pictures a')
					.attr('rel', 'prettyPhoto[pp_gal]')
					.prettyPhoto({
						show_title: false,
						theme: 'light_rounded',
						social_tools: ''
					})
					.each(function () { return false; });
			}

			// Append the image preload
			if (background.backgroundImage != 'none')
				$('#background_container .preload').append('<img src="'+custom_bkg_image+'" />');

			// Creation de l'index
			index.push({
				dom: new_page,
				start: new_page.offset().top,
				end: Math.max(new_page.offset().top + new_page.outerHeight(), page_height)
			});

			flag_next_article = false;

		});
	}

	// AJAX call to retreive the data
	function get_article () {
		if (!isset(ajax_url))
			return $.Deferred().reject();
		return $.get( ajax_url , {category: $('body').attr('data-category'), avoid: articles_read, first: $('body').attr('data-first')} ).done(function (data) {
			if (data.length) {
				data = JSON.parse(data);
				articles_read.push(data.id);
			}
		});
	}

	// Enable positioning
	function active_admin () {
		
		if (!$('.page.current').length || !$('.page.current .to_shift').length)
			return false;

		if ($('#adminbar:visible').length == 0)
			$('#adminbar').slideDown();

		if ($('#map_container:visible').length)
			$('.admin-map').show();
		else
			$('.admin-map').hide();

		$('#adminbar input[name=block_shift_v]').val($('.page.current .to_shift').css('margin-top').replace('px', ''));
		$('#adminbar input[name=block_shift_h]').val($('.page.current .to_shift').css('margin-left').replace('px', ''));
		$('#adminbar input[name=block_size]').val($('.page.current .block').css('width').replace('px', ''));
		$('#adminbar input[name=map_shift_v]').val($('#map_container .to_shift').css('top').replace('px', ''));
		$('#adminbar input[name=map_shift_h]').val($('#map_container .to_shift').css('left').replace('px', ''));

		$('.page.current .block').resizable({
			handles: "e",
			start: function ( event, ui) {
				$('.block.selected').removeClass('selected');
				$(this).addClass('selected');
				if ($('.block.selected').hasClass('custom')) {
					var base_v = parseInt($('.block.selected').css('margin-top').replace('px', ''));
					var base_h = parseInt($('.block.selected').css('margin-left').replace('px', ''));
					$('#adminbar .admin-block').hide();
					$('#adminbar .admin-block-custom').show();
					$('#adminbar input[name=block_shift_v]').val(base_v+$('.block.selected').offset().top);
					$('#adminbar input[name=block_shift_h]').val(base_h+$('.block.selected').offset().left);
				} else {
					var base_v = parseInt($('.page.current .to_shift').css('margin-top').replace('px', ''));
					var base_h = parseInt($('.page.current .to_shift').css('margin-left').replace('px', ''));
					$('#adminbar .admin-block').show();
					$('#adminbar .admin-block-custom').hide();
					$('#adminbar input[name=block_shift_v]').val(base_v+$('.page.current .to_shift').offset().top);
					$('#adminbar input[name=block_shift_h]').val(base_h+$('.page.current .to_shift').offset().left);
				}
			},
			resize: function ( event, ui ) {
				if ($('.block.selected').hasClass('custom')) {
					var string = '[' + next_code + ' ' +
									'w=' + ui.size.width + ' ' +
									'v=' + $('.block.selected').offset().top + ' ' +
									'h=' + $('.block.selected').offset().left +
					']';
					$('#adminbar input[name=block_custom]').val(string);
				} else {
					$('#adminbar input[name=block_size]').val(ui.size.width);
				}
			},
		});
		$('.page.current .to_shift').draggable({
			start: function ( event, ui) {
				$('.page.current .block.selected').removeClass('selected');
				$('.page.current .block:not(.custom)').addClass('selected');
				$('#adminbar input[name=block_size]').val($('.page.current .block.selected').css('width').replace('px', ''));
				if ($('.page.current .block.selected').hasClass('custom')) {
					$('#adminbar .admin-block').hide();
					$('#adminbar .admin-block-custom').show();
				} else {
					$('#adminbar .admin-block').show();
					$('#adminbar .admin-block-custom').hide();
				}
			},
			drag: function ( event, ui ) {
				var base_v = parseInt(ui.helper.css('margin-top').replace('px', ''));
				var base_h = parseInt(ui.helper.css('margin-left').replace('px', ''));
				$('#adminbar input[name=block_shift_v]').val(base_v+ui.position.top);
				$('#adminbar input[name=block_shift_h]').val(base_h+ui.position.left);
			}
		});
		$('.page.current .block.custom').draggable({
			start: function ( event, ui) {
				$('.page.current .block.selected').removeClass('selected');
				$(this).addClass('selected');
				$('#adminbar input[name=block_size]').val($('.page.current .block.selected').css('width').replace('px', ''));
				if ($('.page.current .block.selected').hasClass('custom')) {
					$('#adminbar .admin-block').hide();
					$('#adminbar .admin-block-custom').show();
				} else {
					$('#adminbar .admin-block').show();
					$('#adminbar .admin-block-custom').hide();
				}
			},
			drag: function ( event, ui ) {
				$('#adminbar input[name=block_shift_v]').val(ui.position.top);
				$('#adminbar input[name=block_shift_h]').val(ui.position.left);
				var string = '[' + next_code + ' ' +
								'w=' + $('.page.current .block.selected').css('width').replace('px', '') + ' ' +
								'v=' + ui.position.top + ' ' +
								'h=' + ui.position.left +
				']';
				$('#adminbar input[name=block_custom]').val(string);
			}
		});
		var start_v, start_h;
		$('#map_container .to_shift').draggable({
			drag: function ( event, ui ) {
				$('#adminbar input[name=map_shift_v]').val(ui.position.top);
				$('#adminbar input[name=map_shift_h]').val(ui.position.left);
			}
		});
	}

	// Parse and display the content when
	// in a complex structure
	function parse_content (object) {
		var container = $('.page.next .content');
		$.each(object, function (i, content) {
			if (i == 0) {
				container.html('<div class="content_elt">'+content.text+'</div>');
				return true;
			}
			if (isset(content.w) || isset(content.h) || isset(content.v)) {
				var custom_width = isset(content.w) ? parseInt(content.w, 10) : 0,
					block_width = custom_width ? custom_width : page_width/2;
				var new_container = $('<div class="custom block">').
					css({
						width: block_width,
						top: isset(content.v) ? content.v : 0,
						left: isset(content.h) ? content.h : 0,
						marginTop: 100,
						marginLeft: page_width/2 + parseInt($('.page.next .block:not(.custom)').css('margin-left').replace('px', ''), 10),
					}).
					html('<div class="content_elt">'+content.text+'</div>').
					appendTo('.page.next .to_shift').hide();
				container.find('.content_elt:last').append('<p class="next_block">Suite →</p>');
				container = new_container;
			} else {
				container.append('<div class="content_elt" style="display:none;">'+content.text+'</div>');
				container.find('.content_elt:last').prev().append('<p class="next_elt">Suite →</p>');
			}
		});
	}

	// Handle the scroll to load new articles
	// or display the background
	function scroll_controller () {
		
		var map_opacity_max = 0.8;

		var is_transition = true;
		var before = current = 0,
			after = index.length-1;
		var bkg_opacity = parseFloat($('#background_container .transition').css('opacity'));
		var map_opacity = parseFloat($('#map_container').css('opacity'));
		var map_pointer = parseFloat($('#map_container .pointer').css('left').replace('px', ''));

		// Get the scroll position
		scroll_top = $(this).scrollTop();
		old_scroll_top = scroll_top;

		// Find out what is the current state
		$.each(index, function (i, value) {
			before = value.start <= scroll_top && value.start > index[before].start ? i : before; // article before the scroll position
			after = scroll_top <= value.start && value.start < index[after].start ? i : after; // article after the scroll position
			if (value.start-scroll_top < page_height/2 && value.end-scroll_top > page_height/2) {
				is_transition = false;
				current = i; // current article
				current_page = i;
			}
		});

		// If the scroll is situated in a transition
		if (is_transition) {

			var transition_value = scroll_top - index[before].end + page_height/2,
				transition_total = index[after].start - index[before].end,
				pourcent = transition_value/transition_total,
				pourcent_round = Math.round(pourcent*100)/100;

			var is_map_before = isset(index[before]) && isset(index[before].dom.attr('data-map')) && index[before].dom.attr('data-map').length,
				is_map_after = isset(index[after]) && isset(index[after].dom.attr('data-map')) && index[after].dom.attr('data-map').length;
			var map_before = is_map_before ? index[before].dom.attr('data-map').split('|') : [0, 0],
				map_after = is_map_after ? index[after].dom.attr('data-map').split('|') : [0, 0],
				pointer_before = is_map_before && isset(index[before].dom.attr('data-map-pointer')) ? index[before].dom.attr('data-map-pointer').split('|') : [0, 0],
				pointer_after = is_map_after && isset(index[after].dom.attr('data-map-pointer')) ? index[after].dom.attr('data-map-pointer').split('|') : [0, 0],
				mapshift_before = is_map_before && isset(index[before].dom.attr('data-map-shift')) ? index[before].dom.attr('data-map-shift').split('|') : [0, 0],
				mapshift_after = is_map_after && isset(index[after].dom.attr('data-map-shift')) ? index[after].dom.attr('data-map-shift').split('|') : [0, 0];
			
			// Change the background
			if (isset(index[before]) && $('#background_container').css('background') != index[before].dom.attr('data-background')) {
				$('#background_container').css({
					background: index[before].dom.attr('data-background')
				});
			}
			if (isset(index[after]) && Math.round(bkg_opacity*100) != Math.round(pourcent*100)) {
				$('#background_container .transition').css({
					background: index[after].dom.attr('data-background'),
					opacity: pourcent_round
				});
			}

			// Change the map
			if ((is_map_before && !is_map_after) || (!is_map_before && is_map_after)) { // map appearing / disappearing
				$('#map_container').css({
					opacity: (is_map_after ? pourcent_round : (1 - pourcent_round)) * map_opacity_max
				});
			}
			if (is_map_after) { // map positioning

				var position_map = {
					marginLeft: parseFloat(map_after[0])*pourcent_round + (1-pourcent_round)*parseFloat(map_before[0]),
					marginTop: parseFloat(map_after[1])*pourcent_round + (1-pourcent_round)*parseFloat(map_before[1]),
					left: parseFloat(mapshift_after[0])*pourcent_round + (1-pourcent_round)*parseFloat(mapshift_before[0]),
					top: parseFloat(mapshift_after[1])*pourcent_round + (1-pourcent_round)*parseFloat(mapshift_before[1])
				};
				var position_pointer = {
					left: parseFloat(pointer_after[0])*pourcent_round + (1-pourcent_round)*parseFloat(pointer_before[0]),
					top: parseFloat(pointer_after[1])*pourcent_round + (1-pourcent_round)*parseFloat(pointer_before[1])
				};
				
				$('#map_container .pointer').css({
					left: position_pointer.left + 'px',
					top: position_pointer.top + 'px'
				});
				$('#map_container .to_shift').css({
					marginLeft: position_map.marginLeft + 'px',
					marginTop: position_map.marginTop + 'px',
					left: position_map.left + 'px',
					top: position_map.top + 'px'
				});
			}
			
			// Load a new article
			if (!flag_next_article && after == index.length-2)
				get_next_article();

		} else {

			var is_map_current = isset(index[current]) && isset(index[current].dom.attr('data-map')) && index[current].dom.attr('data-map').length;
			var map_current = is_map_current ? index[current].dom.attr('data-map').split('|') : [0, 0],
				pointer_current = is_map_current && isset(index[current].dom.attr('data-map-pointer')) ? index[current].dom.attr('data-map-pointer').split('|') : [0, 0],
				mapshift_current = is_map_current && isset(index[current].dom.attr('data-map-shift')) ? index[current].dom.attr('data-map-shift').split('|') : [0, 0];

			if ($('#background_container').css('background') != index[current].dom.attr('data-background') ||
				$('#background_container .transition').css('opacity') != 0) {
				$('#background_container').css({
					background: index[current].dom.attr('data-background')
				});
				$('#background_container .transition').css('opacity', 0);
			}
			if (!is_map_current) {
				$('#map_container').css('opacity', 0);
			} else {
				$('#map_container .pointer').css({
					left: pointer_current[0] + 'px',
					top: pointer_current[1] + 'px'
				});
				$('#map_container .to_shift').css({
					marginLeft: map_current[0] + 'px',
					marginTop: map_current[1] + 'px',
					left: mapshift_current[0] + 'px',
					top: mapshift_current[1] + 'px'
				});
			}
		}

		
	}

	// Check if a variable is defined
	function isset (variable) {
	    return !(typeof(variable)=='undefined' || variable===null);
	}
});