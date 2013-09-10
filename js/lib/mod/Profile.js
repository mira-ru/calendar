lib.module('mod.Profile');

var Profile = function() {
	function initSlider() {
		var slider = $('.slider'),
		    images = slider.find('.slider-images > div'),
		    controls = slider.find('.slider-controls > div'),
		    qnt = controls.size();
	
		function _toogleSlide(pos) {
			var current = controls.filter('.current');
			    pos = (typeof pos != 'undefined') ? pos : current.index() + 1;
			    pos = (pos >= qnt) ? 0 : pos;
			    controls
				.removeClass('current')
				.eq(pos)
				.addClass('current');
			    images
			    	.eq(pos)
			    	.animate({'opacity': 1}, 400)
			    	.siblings()
			    	.animate({'opacity': 0}, 400)
		}
		slider.on('click', '.slider-controls div:not(.current) a', function() {
			_toogleSlide($(this).parent().index());
			clearInterval(timer);
			return false;
		});
		var timer = setInterval(_toogleSlide, 3000);
	}
	return {
		initSlider:initSlider
	}
}();
$(function(){
	Profile.initSlider();
});