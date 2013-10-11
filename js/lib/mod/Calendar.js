// Definition
lib.module('mod.Calendar');

lib.include('mod.Common', lib.version);

lib.include('plugins.bootstrap.Modal');

//lib.include('plugins.bootstrap.Transition');

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

		$('.timeline-days').on('click', 'span:not(.current)', function(e){
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
			_getEvents(_moduleOptions);
			_setFilterLabel(text);
			li.parent().hide();
		}).on('click', '[data-id]', function(){
				var li = $(this),
				    id = li.data('id');

				setOptions({'activity_id':id, 'service_id':0});
				_getEvents(_moduleOptions);
				_setFilterLabel(li.text());
				li.parent().hide();
			}).on('click', 'i', function(e){
				e.stopImmediatePropagation();
				_resetFilter();
			}).on('mouseover', 'span', function(){
				$('ul', $(this)).show();
			}).on('mouseout', 'span', function(){
				$('ul', $(this)).hide();
			});

		$('.timeline-wrapper').on('click', 'div[class^="col-"]:not(.empty)', function(e){
			e.stopImmediatePropagation();
			var toggler = $(this),
				pos = toggler.offset(),
				balloon = $('.event-balloon');

			// Получаем данные
			var data = _moduleOptions;
			data.event_id = toggler.data('event');
			var request = $.ajax({
				url: '/site/axEvent',
				type: 'POST',
				data: data,
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
			if ($(e.target).data('toggle') != 'modal' || typeof $(e.target).data('sub') === 'undefined') {
				$('.event-balloon').hide('fast', function(){
					$(this).removeAttr('style').children('div').empty();
				});
			}
		});

		$( window ).bind( 'load', function( event ) {
			setTimeout( function(){
				$( window ).bind( 'popstate', function( event ) {
					location.reload();
				});
			},0);
		});

		$('.modal')
			.on('shown.bs.modal', function(e) {
				var ev = $(e.relatedTarget),
				    str = (ev.data('master-id')) ? 'm='+ev.data('master-id') : (ev.data('action-id')) ? 'a='+ev.data('action-id') : null ;
				_changeUrl(_moduleOptions, str);
			})
			.on('hide.bs.modal', function() {
				_changeUrl(_moduleOptions);
			})
			.on('hidden.bs.modal', function() {
				$(this).removeData('bs.modal').empty();
			});

		// Загрузка событий в .timeline-wrapper

		function _getEvents(data) {
			var     content = $('.timeline-wrapper>div'),
				days = $('.timeline-days tr'),
				period = $('.period-links a');

			content.addClass('-loading');
			var request = $.ajax({
				url: '/site/axEvents',
				type: 'POST',
				data: data,
				dataType: 'json'
			});
			request.done(function(msg) {
				content.removeClass('-loading');
				var week = msg.week;
				if (msg.html.length == 0) {
					content.html(msg.html);
				}
				else {
					content.html(msg.html);
				}
				if(msg.days.length > 0){
					days.html(msg.days);
				}

				if(_moduleOptions.activity_id > 0){
					var urlWithoutDate = '/c/'+_moduleOptions.center_id+'/'+_moduleOptions.service_id+'/'+_moduleOptions.activity_id;
					$(period[2]).attr('href',urlWithoutDate + '/' + msg.week.prev).attr('data-time', msg.week.prev);
					$(period[3]).attr('href',urlWithoutDate + '/' + msg.week.next).attr('data-time', msg.week.next);
				}
				var layout = (_moduleOptions.activity_id > 0) ? 1 : 0;
				_toggleLayout(layout);
			});
		}


		function _toggleLayout(toggle){
			if(toggle){
				$('#wrap').addClass('week-view');
				$('.timeline-hours tr:first').hide();
			} else {
				$('#wrap').removeClass('week-view');
				$('.timeline-hours tr:first').show();
			}
		}

		// Установка фильтра
		function _setFilterLabel(text) {
			$('.filter-items').empty();
			filter = $('<li>').appendTo('.filter-items').text(text).wrapInner('<span>').append('<i>').find('i').bind('click', _resetFilter);
		}
		$('.filter-items i').bind('click', _resetFilter);

		// Сброс фильтра
		function _resetFilter() {
			$('.filter-items').empty();
			setOptions({'activity_id':0, 'service_id':0});
			// Обновляем таймлайн и расписание
			_getEvents(_moduleOptions);
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
	function _changeUrl(data, get) {
		var     url = '/c/'+data.center_id+'/'+data.service_id+'/'+data.activity_id+'/'+data.day,
			urlWithoutCenter = '/0/0/'+data.day,
			urlWithoutDate = '/c/'+data.center_id+'/'+data.service_id+'/'+data.activity_id,
			menuLinks = $('.top-menu li:not(.current) a'),
			periodLinks = $('.period-links a');

		url = (typeof get !== 'undefined') ? url + '?' + get : url;
		if(window.history && history.pushState){
			history.pushState(null, null, url);
		} else {
			location.hash = url;
		}

		menuLinks.each(function(){
			var id= $(this).attr('data-center');
			$(this).attr('href','/c/' + id + urlWithoutCenter);
		});
		periodLinks.each(function(){
			var time = $(this).attr('data-time');
			$(this).attr('href',urlWithoutDate + '/' + time);
		});
	}

	// Mapping
	return {
		initialize:initialize,
		reloadWithHash:reloadWithHash,
		setOptions:setOptions
	};
}();

$(function(){
	var getStr = location.search;
	if (getStr.getQueryKey('m', true) || getStr.getQueryKey('a', true)) {
		var t = (getStr.getQueryKey('m', true)) ? 'm' : (getStr.getQueryKey('a', true)) ? 'a' : false ;
		var v = (t == ('m' || 'a')) ? getStr.getQueryKey('m') : getStr.getQueryKey('a') ;
		var m = $('.modal'), getSubstr = '?type=' + t + '&item=' + v;
		m.modal({
			show: true,
			remote: '/site/axPopup' + getSubstr
		});
	}
});
