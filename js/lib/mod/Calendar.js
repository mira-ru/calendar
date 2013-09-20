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

		$('.timeline-days').on('click', 'span:not(.disabled)', function(e){
			var span = $(this),
			    month = $('strong.current').data('month'),
			    year = $('strong.current').data('year'),
			    day = $('i', span).data('day'),
			    current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');

			var request = $.ajax({
				url: '/site/axEvents',
				type: 'POST',
				data: { day_timestamp : day , center_id: _moduleOptions.center_id, activity_id: _moduleOptions.activity_id },
				dataType: 'json'
			});
			request.done(function( msg ) {
				$( '.timeline-wrapper' ).html( msg.html );
			});
		});

		$('[data-id]').on('click', function(){
			var li = $(this),
			    id = li.data('id'),
			    row = $('.timeline-row'),
			    sub = $('div', row),
			    label, key_map, ids = (id == 0) ? li.siblings() : li ;

			key_map = ids.map(function(){
				return $(this).data('id');
			}).get();

			// Отправляем данные для маппинга фильтра направлений в днях месяца
			if (id != 0) {
				var days = $('.timeline-days i'),
				    request = $.ajax({
					url: '/site/axActiveDays',
					type: 'POST',
					data: { current_month : _moduleOptions.current_month, center_id: _moduleOptions.center_id, activity_id: id },
					dataType: 'json'
				});
				request.done(function( msg ) {
					days.each(function(index){

						if (jQuery.inArray($(this).data('day'), msg.days) == -1) {
							$(this).parent().addClass('disabled');
						} else {
							$(this).parent().removeClass('disabled');
						}
					});
				});
			}
			else {
				$('.timeline-days span.disabled').removeClass('disabled');
			}

			_moduleOptions.activity_id = id;

			// Делаем маппинг занятий
		 	sub.map(function(){
		 		var key = $(this).data('sub');
		 		
		 		if (jQuery.inArray(key, key_map) != -1) {
		 			return this;
		 		}
		 	}).promise().done(function(){
		 		var elems = $(this);
				if (elems.length != 0) $('.warning-empty').fadeOut();
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
							$('.timeline-wrapper > div').slideDown('fast', function(){
								$('div', $(this)).fadeIn('fast');
								$('.filter-items').empty();
							});
						});
					}
					else {
						$('.timeline-wrapper > div').slideDown('fast', function(){
							$('div', $(this)).fadeIn('fast');
							$('.filter-items').empty();
						});
					}
					$('.timeline-days span.disabled').removeClass('disabled');
					_moduleOptions.activity_id = 0;
				});
			}
		});
		
		$('[data-sub]').on('click', 'span', function(){
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
				data: { event_id: div.data('event') },
				dataType: 'json'
			});
			request.done(function( msg ) {
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
				balloon.hide('fast', function(){
					// clk.prev().empty();
				});
			})
		});

		$('.prev-month, .next-month').on('click', function(){
			var day = $('.timeline-days .current i').data('day'),
			    month = $(this).data('month'),
			    year = $(this).data('year'),
			    date = year + '-' + month + '-' + day;
		});
	}

	// Mapping
	return {
		initialize:initialize
	};
}();
