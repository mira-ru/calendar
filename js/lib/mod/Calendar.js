// Definition
lib.module('mod.Calendar');

// Dependencies
lib.include('plugins.bootstrap.Button');

// Class definition
var Calendar = function () { 'use strict';

	// Public method
	function initialize () {
		
		var filter;

		$('.timeline-days').on('click', 'span', function(e){
			var span = $(this),
			    month = $('[data-month]').data('month'),
			    year = $('[data-year]').data('year'),
			    current = $('.current', $(e.delegateTarget));
			span.add(current).toggleClass('current');
			var date = span.find('i').text() + '-' + month + '-' + year;
			console.log(date);
		});

		$('[data-id]').on('click', function(){
			var li = $(this),
			    id = li.data('id'),
			    row = $('.timeline-row'),
			    sub = $('div', row),
			    // sub = $('div', row).not('[data-sub="' + id + '"]'),
			    label, key_map, ids = (id == 0) ? li.siblings() : li ;

			key_map = ids.map(function(){
				return $(this).data('id');
			}).get();

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
		});
		function _setFilterLabel(text) {
			$('.filter-items').empty();
			filter = $('<li>').appendTo('.filter-items').text(text).wrapInner('<span>').append('<i>').find('i').bind('click', function(){
				$('.timeline-wrapper > div').slideDown('fast', function(){
					$('div', $(this)).fadeIn('fast');
					$('.filter-items').empty();
				});
			});
		}
	}

	// Mapping
	return {
		initialize:initialize
	};
}();
