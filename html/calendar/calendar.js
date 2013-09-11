steal(function(){
	steal.config({
		//root: '../../js/',
	});
}).then('jquery', 'less', '../../js/lib/plugins/bootstrap/Button.js', function(){
	$('.btn-group').on('click', '.btn', function(e) {
		var btn = $(e.currentTarget),
		    s = btn.find('input').attr('id'),
		    td = $('.table td'),
		    visible = td.filter(':visible'),
		    hidden = td.filter(':hidden'),
		    rest = td.filter(':not(.' + s + ')'),
		    needed = td.filter('.' + s);
		if (hidden.size() == 0) {
			if (s != 'all') {
				rest.fadeOut('fast');
			}
		}
		else {
			if (s == 'all') {
				td.fadeIn('fast');
			}
			else {
				visible.fadeOut('fast', function(){
					needed.fadeIn('fast');
				})
			}
		}
	});	
});