// Definition
lib.module('mod.Calendar');

lib.include('mod.Common');

// Class definition
var Calendar = function () { 'use strict';
	var _moduleOptions = {
		'day': 0,
		'activity_id': 0,
		'center_id': 0,
		'service_id': 0,
		'month': 0
	};
	// Public method
	function initialize (options) {
		$.extend(true, _moduleOptions, options)
		
		var filter;

		$('.timeline-days').on('click', 'span:not(.disabled, .current)', function(e){
			var span = $(this),
			    day = $('i', span).data('day'),
			    current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');
			_moduleOptions.day = day;
			_getEvents(_moduleOptions);
			$('.event-balloon').hide('fast');
		});

		$('[data-service]').on('click', function(){
			var li = $(this),
			    sid = li.data('service'),
			    text = li.parent().prev().text() + ' (' + (li.text() + '').toLowerCase() + ')';
			_moduleOptions.service_id = sid;
			_moduleOptions.activity_id = 0;
			_updateTimelineDays(_moduleOptions);
			_filterEvents(text, 0, sid);
		});

		$('[data-id]').on('click', function(){
			var li = $(this),
			    id = li.data('id');
			_moduleOptions.activity_id = id;
			_moduleOptions.service_id = 0;
		 	_updateTimelineDays(_moduleOptions);
			_filterEvents(li.text(), id, 0);
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

			// $('.pencil', balloon).bind('click', function(){
			// 	var clk = $(this);
			// 	balloon.hide('fast');
			// });
		});

		$('body').on('click', function(){
			$('.event-balloon').hide('fast');
		});

		function _filterEvents(text, id, sid) {
			var row = $('.timeline-row'),
			    sub = $('div', row),
			    val = (sid == 0) ? id : sid ;
			
			// Делаем маппинг занятий
		 	sub.map(function(){
		 		var key = (sid == 0) ? $(this).data('sub') : $(this).data('sid') ;
		 		if (key == val) {
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
			 			_setFilterLabel(text);
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
			 			_setFilterLabel(text);
			 			if (elems.length == 0) {
							$('.warning-empty').fadeIn();
						}
			 		});
		 		}
		 	});			
		}

		// Обновление .timeline-wrapper

		function _updateTimelineDays(data) {
			var days = $('.timeline-days span'),
			    request = $.ajax({
				url: '/site/axActiveDays',
				type: 'POST',
				data: data,
				dataType: 'json'
			});
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

		// Установка фильтра

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
				_moduleOptions.service_id = 0;
				// Обновляем таймлайн
				_updateTimelineDays(_moduleOptions);
			});
		}
	}

	// Mapping
	return {
		initialize:initialize
	};
}();
