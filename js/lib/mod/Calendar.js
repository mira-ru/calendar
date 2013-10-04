// Definition
lib.module('mod.Calendar');

lib.include('mod.Common');

// Class definition
var Calendar = function () { 'use strict';
	var _moduleOptions = {
		'day': 0,
		'activity_id': 0,
		'center_id': 0,
		'service_id': 0
	};
	// Public method
	function initialize (options) {
		$.extend(true, _moduleOptions, options);

		var filter;

		$('.timeline-days').on('click', 'span:not(.disabled, .current)', function(e){
			var span = $(this),
				day = $('i', span).data('day'),
				current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');
			setOptions({'day':day});
			_getEvents(_moduleOptions);
			$('.event-balloon').hide('fast');
		});

		$('.sub-menu').on('click', '[data-service]', function(){
			var li = $(this),
				sid = li.data('service'),
				text = li.parent().prev().text() + ' (' + (li.text() + '').toLowerCase() + ')';
			setOptions({'service_id':sid, 'activity_id':0});
			_updateTimelineDays(_moduleOptions);
			_filterEvents(text, 0, sid);
			li.parent().hide();
		}).on('click', '[data-id]', function(){
				var li = $(this),
					id = li.data('id');

				setOptions({'activity_id':id, 'service_id':0});
				_updateTimelineDays(_moduleOptions);
				_filterEvents(li.text(), id, 0);
				li.parent().hide();
			}).on('click', 'i', function(e){
				e.stopImmediatePropagation();
				_resetFilter();
			}).on('mouseover', 'span', function(){
				$('ul', $(this)).show();
			}).on('mouseout', 'span', function(){
				$('ul', $(this)).hide();
			});

		$('.timeline-wrapper').on('click', 'div[class^="col-"]', function(e){
			e.stopImmediatePropagation();
			var toggler = $(this),
				pos = toggler.offset(),
				balloon = $('.event-balloon');

			// Получаем данные
			var request = $.ajax({
				url: '/site/axEvent',
				type: 'POST',
				data: {
					event_id: toggler.data('event')
				},
				dataType: 'json'
			});
			request.done(function(msg) {
				balloon.find('div').html(msg.html).end().css({opacity: 0, display: 'block'});
				var left = pos.left - (balloon.outerWidth() / 2) + (toggler.outerWidth() / 2),
					top = pos.top - (balloon.outerHeight() / 2) + (toggler.outerHeight() / 2),
					bw = balloon.outerWidth(), row = toggler.parent(), ol = row.offset().left, or = ol + row.outerWidth(),
					bl = bw + left,
					o = (ol > left) ? ol : (or < bl) ? or - bw : left ;
				balloon.css({top: top, left: o}).animate({opacity: 1}, 'fast');
			});
		});

		$('body').on('click', function(e){
			if (typeof $(e.target).data('sub') === 'undefined') {
				$('.event-balloon').hide('fast', function(){
					$(this).removeAttr('style').children('div').empty();
				});
			}
		});

		window.setTimeout(function() {
			window.addEventListener("popstate", function(e) {
				location.reload();
			}, false);
		}, 1);

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
			filter = $('<li>').appendTo('.filter-items').text(text).wrapInner('<span>').append('<i>').find('i').bind('click', _resetFilter);
		}
		$('.filter-items i').bind('click', _resetFilter);
		// Сброс фильтра

		function _resetFilter() {
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
			setOptions({'activity_id':0, 'service_id':0});
			// Обновляем таймлайн
			_updateTimelineDays(_moduleOptions);
		}
	}

	function reloadWithHash(){
		var hash = location.hash;
		if(hash.length > 0){
			location = hash.replace('#','');
		}
	}

	function setOptions(options){
		$.extend(true, _moduleOptions, options);
		_changeUrl(_moduleOptions);
	}

	// замена url
	function _changeUrl(data) {
		var     url = '/c/'+data.center_id+'/'+data.service_id+'/'+data.activity_id+'/'+data.day,
			urlWithoutCenter = '/0/0/'+data.day,
			urlWithoutDate = '/c/'+data.center_id+'/'+data.service_id+'/'+data.activity_id;
		if(window.history && history.pushState){
			history.pushState(null, null, url);


		}else{
			location.hash = url;
		}

		var     menuLinks = $('.top-menu li:not(.current) a'),
			prevMoth = $('.prev-month'),
			nextMoth = $('.next-month');

		menuLinks.each(function(){
			var id= $(this).attr('data-center');
			$(this).attr('href','/c/' + id + urlWithoutCenter);
		});

		prevMoth.attr('href',urlWithoutDate + '/' + prevMoth.attr('data-time'));
		nextMoth.attr('href',urlWithoutDate + '/' + nextMoth.attr('data-time'));
	}

	// Mapping
	return {
		initialize:initialize,
		reloadWithHash:reloadWithHash,
		setOptions:setOptions
	};
}();
