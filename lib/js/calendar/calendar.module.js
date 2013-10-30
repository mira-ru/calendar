(function(require){
	var Calendar = {
		state : {
			day: 0,
			type: '',
			item: 0,
			center_id: 0,
			search: false
		},
		filter : ''
	};

	Calendar.initialize = function(){
		$.extend(true, Calendar.state, defaultState);
		$('#days').on('click', 'li:not(.disabled, .current)', function(e){
			var span = $(this),
				day = $('i', span).data('day'),
				current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');
			Calendar.setOptions({'day':day});
			_getEvents(Calendar.state);
			$('#popover').hide('fast');
		});

		$('#centers').on('click', '.current', function(){
			return false;
		});

		$('#services').on('click', 'span', function(e){
			$('.expanded', e.delegateTarget).add(this).toggleClass('expanded');
		}).on('click', '[data-service]', function(e){
				e.stopImmediatePropagation();
				var li = $(this),
					sid = li.data('service'),
					text = li.parent().prev().text() + ' (' + (li.text() + '').toLowerCase() + ')';

				Calendar.setOptions({type:'service', item:sid});
				_getEvents(Calendar.state);
				_setFilterLabel(text);

				li.parent().fadeOut('fast', function(){
					$('.expanded').removeClass('expanded');
					$(this).removeAttr('style');
				});
			}).on('click', '[data-id]', function(e){
				e.stopImmediatePropagation();
				var li = $(this),
					id = li.data('id');

				Calendar.setOptions({type:'activity', item:id});
				_getEvents(Calendar.state);
				_setFilterLabel(li.text());

				li.parent().fadeOut('fast', function(){
					$('.expanded').removeClass('expanded');
					$(this).removeAttr('style');
				});
			});

		$('#events .events-wrapper').on('click', 'div[class^="col-"]:not(.empty, .event-time, .event-info)', function(e){
			e.stopImmediatePropagation();
			var toggler = $(this),
				pos = toggler.offset(),
				balloon = $('#popover');

			// Получаем данные
			var data = Calendar.state;
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
				$('#popover').hide('fast', function(){
					$(this).removeAttr('style').children('div').empty();
				});
			}
			var match = $(e.target).closest('.expanded');
			if (!match.length){
				$('.expanded').removeClass('expanded');
			}
		});
		$('#search').find('input').autocomplete({
			source:'/ajax/search',
			minLength: 2,
			appendTo: "#search div",
			select: function( event, ui ) {
				Calendar.setOptions({type:ui.item.type,item:ui.item.item});
				_getEvents(Calendar.state);
				_changeUrl(Calendar.state, 'search='+ui.item.label);
				$('.list-inline li.current').removeClass('current');
				$('#services').slideUp('fast');
				Calendar.state.search = true;
				if (typeof yaCounter22425796 !== 'undefined') { yaCounter22425796.reachGoal('search'); }
			},
			position: {
				my:'left top+10',
				at: "right bottom"
			}
		}).keyup(function(){
				if($(this).val().length > 2)
					$(this).siblings('i').fadeIn();
				else
					$(this).siblings('i').fadeOut();
			})
			.end()
			.find('i').click(function(){
				if(Calendar.state.search) {
					location.href = '/c/'+Calendar.state.day;
				} else {
					$(this).siblings('input').val('');
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
				if (str != null) {
					_changeUrl(Calendar.state, str);
				}
			})
			.on('hide.bs.modal', function() {
				_changeUrl(Calendar.state);
			})
			.on('hidden.bs.modal', function() {
				$(this).removeData('bs.modal').empty();
			});

		$('#filter i').bind('click', _resetFilter);
	};

	Calendar.reloadWithHash = function(){
		var hash = location.hash;
		if(hash.length > 0){
			location.href = hash.replace('#','');
		}
	};

	Calendar.setOptions = function(options){
		$.extend(true, Calendar.state, options);
		_changeUrl(Calendar.state);
	};

	//подгрузка событий
	function _getEvents(data) {
		var     content = $('.events-wrapper'),
			days = $('#days'),
			period = $('#period a');

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
			if(Calendar.state.type != 'center' && Calendar.state.type != 'service'){
				var     params = '/' + data.type + '/' + data.item;
				$(period[2]).attr('href','/c/' + msg.week.prev + params).attr('data-time', msg.week.prev);
				$(period[3]).attr('href','/c/' + msg.week.next + params).attr('data-time', msg.week.next);
			}
			_toggleLayout(msg.layout);
		});
	}

	//смена вида
	function _toggleLayout(layout){
		if(layout.length > 0){
			$('#wrap').removeClass('week-view list-view').addClass(layout);
			if(layout == 'list-view')
				$('#days').hide();
		} else {
			$('#wrap').removeClass('week-view list-view');
			$('#days').show();
		}
	}

	// Установка фильтра
	function _setFilterLabel(text) {
		var filter = $('#filter');
		filter.empty();
		Calendar.filter = $('<li>').appendTo(filter).text(text).wrapInner('<span>').append('<i>').find('i').bind('click', _resetFilter);
	}

	// Сброс фильтра
	function _resetFilter() {
		$('#filter').empty();
		Calendar.setOptions({type:'center', item:Calendar.state.center_id});
		// Обновляем таймлайн и расписание
		_getEvents(Calendar.state);
	}

	// замена url
	function _changeUrl(data, get) {
		var     params = (data.type != '' && data.item != 0) ? '/' + data.type + '/' + data.item : '',
			url = '/c/'+data.day + params,
			menuLinks = $('#centers li:not(.current) a'),
			periodLinks = $('.period-links a');
		url = (typeof get !== 'undefined') ? url + '?' + get : url;
		if(window.history && history.pushState){
			history.pushState(null, null, url);
		} else {
			location.hash = url;
		}

		menuLinks.each(function(){
			var id= $(this).attr('data-center');
			$(this).attr('href','/c/' + data.day + '/center/'+ id);
		});
		periodLinks.each(function(){
			var time = $(this).attr('data-time');
			$(this).attr('href','/c/' + time + params);
		});
	}
	return Calendar;
})




