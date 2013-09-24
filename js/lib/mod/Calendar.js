// Definition
lib.module('mod.Calendar');

// Dependencies
lib.include('plugins.bootstrap.Button');

// Class definition
var Calendar = function () { 'use strict';
	var _moduleOptions = {
		'center_id': 0,
		'activity_id': 0,
		'current_month': 0
	};
	// Public method
	function initialize (options) {

		$.extend(true, _moduleOptions, options)

		var filter;

		$('.timeline-days').on('click', 'span:not(.disabled, .current)', function(e){
			var span = $(this),
			    // month = $('strong.current').data('month'),
			    // year = $('strong.current').data('year'),
			    day = $('i', span).data('day'),
			    current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');
			// Получаем события
			_getEvents({
				day_timestamp: day,
				center_id: _moduleOptions.center_id,
				activity_id: _moduleOptions.activity_id
			});
		});

		$('[data-service]').on('click', function(){
			var service_id = $(this).data('service');
			// Обновляем таймлайн
			_updateTimelineDays(0, service_id);
			// Получаем события
			_getEvents({
				center_id: _moduleOptions.center_id,
				service_id: service_id
			});
		});

		$('[data-id]').on('click', function(){
			var li = $(this),
			    id = li.data('id'),
			    row = $('.timeline-row'),
			    sub = $('div', row);
			_moduleOptions.activity_id = id;
			// Обновляем таймлайн
			_updateTimelineDays(_moduleOptions.activity_id);
			// Делаем маппинг занятий
		 	sub.map(function(){
		 		var key = $(this).data('sub');
		 		if (key == id) {
		 			return this;
		 		}
		 	}).promise().done(function(){
		 		var elems = $(this);
				if (elems.length != 0) {
					$('.warning-empty').fadeOut();
				}
		 		if (filter) {
		 			// Здесь нужно:
		 			// Ко всем видимым добавить новые, кроме видимых новых и сделать toggle
		 			var arr = sub.filter(':visible').not(elems.filter(':visible')).add(elems.filter(':hidden'));
					arr.fadeToggle('fast').promise().done(function(){
			 			row.each(function(index){
			 				var c = $(this).children('[style="display: block;"], :visible').length;
			 				if (c == 0) {
			 					$(this).parent().slideUp('fast');
			 				}
			 				else {
			 					$(this).parent().slideDown('fast');
			 				}
			 			});
			 			_setFilterLabel(li.text());
			 			if (elems.length == 0) {
							$('.warning-empty').fadeIn();
						}
		 			});
		 		}
		 		else {
			 		sub.not(elems).fadeOut('fast').promise().done(function(){
			 			row.each(function(index){
			 				var c = $(this).children(':visible').length;
			 				if (c == 0) {
			 					$(this).parent().slideUp('fast');
			 				}
			 			});
			 			_setFilterLabel(li.text());
			 			if (elems.length == 0) {
							$('.warning-empty').fadeIn();
						}
			 		});
		 		}
		 	});
			function _setFilterLabel(text) {
				$('.filter-items').empty();
				filter = $('<li>').appendTo('.filter-items').text(text).wrapInner('<span>').append('<i>').find('i').bind('click', function(){
					if ($('.warning-empty').is(':visible')) {
						$('.warning-empty').fadeOut('fast').promise().done(function(){
							$('.timeline-wrapper > div > div').slideDown('fast', function(){
								$('div', $(this)).fadeIn('fast');
							});
							$('.filter-items').empty();
						});
					}
					else {
						$('.timeline-wrapper > div > div').slideDown('fast', function(){
							$('div', $(this)).fadeIn('fast');
						});
						$('.filter-items').empty();
					}
					_moduleOptions.activity_id = 0;
					// Обновляем таймлайн
					_updateTimelineDays(_moduleOptions.activity_id);
				});
			}
		});
		
		$('.timeline-wrapper').on('click', 'span', function(){
			var toggler = $(this),
			    div = toggler.parent(),
			    pos = div.offset(),
			    balloon = $('.event-balloon'),
			    left = pos.left - (balloon.outerWidth() / 2) + (div.outerWidth() / 2),
			    top = pos.top - (balloon.outerHeight() / 2) + (div.outerHeight() / 2);

			// Получаем данные
			var request = $.ajax({
				url: '/site/axEvent',
				type: 'POST',
				data: {
					event_id: div.data('event')
				},
				dataType: 'json'
			});
			request.done(function(msg) {
				if (balloon.is(':visible')) {
					balloon.hide('fast', function(){
						balloon.find('div').html(msg.html).end().css({top: top, left: left}).fadeIn('fast');
					});
				}
				else {
					balloon.find('div').html(msg.html).end().css({top: top, left: left}).fadeIn('fast');
				}
			});

			$('.cross', balloon).bind('click', function(){
				var clk = $(this);
				balloon.hide('fast');
			});
		});

		// $('.prev-month, .next-month').on('click', function(){
		// 	var day = $('.timeline-days .current i').data('day'),
		// 	    month = $(this).data('month'),
		// 	    year = $(this).data('year'),
		// 	    date = year + '-' + month + '-' + day;
		// });

		// Обновление .timeline-wrapper

		function _updateTimelineDays(activityId, serviceId) {
			var days = $('.timeline-days span'),
			    id = serviceId || activityId,
			    flag = (serviceId) ? true : false,
			    request = $.ajax({
				url: '/site/axActiveDays',
				type: 'POST',
				data: {
					current_month: _moduleOptions.current_month, 
					center_id: _moduleOptions.center_id, 
					activity_id: id,
					flag: flag
				},
				dataType: 'json'
			});
			console.log(flag);
			request.done(function(msg) {
				days.each(function(){
					var day = $(this).children('i').data('day');
					if (jQuery.inArray(day, msg.days) == -1) {
						$(this).addClass('disabled');
					} else {
						$(this).removeClass('disabled');
					}
				});
			});
		}

		// Загрузка событий в .timeline-wrapper

		function _getEvents(data) {
			var request = $.ajax({
				url: '/site/axEvents',
				type: 'POST',
				data: data,
				dataType: 'json'
			});
			request.done(function(msg) {
				if (msg.html.length == 0) {
					$('.timeline-wrapper > div').html(msg.html);
					$('.warning-empty').fadeIn();
				}
				else {
					$('.warning-empty').fadeOut(function(){
						$('.timeline-wrapper > div').html(msg.html);
					});
				}
			});			
		}
	}

	// Mapping
	return {
		initialize:initialize
	};
}();
