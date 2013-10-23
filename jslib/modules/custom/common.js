(function(require){
	var Common = {};
	Common.isMobile = {
		Android:function () {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry:function () {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS:function () {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera:function () {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows:function () {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any:function () {
			return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
		}
	};
	Common.getQueryKey = function(key, presence){
		var search = unescape(this.toString()),
			check_presence = presence || false;
		if (search != '') {
			search = search.substr(1);
			var params = search.split('&');
			for (var i = 0; i < params.length; i++) {
				var pairs = params[i].split('=');
				if (pairs[0] == key) {
					return (check_presence) ? true : pairs[1] ;
				}
			}
		} else {
			return (check_presence) ? false : null ;
		}

	};

	Common.showError =function(){
		$('div#error').html(msg).fadeIn(function(){
			var err = $(this);
			setTimeout(function(){
				err.fadeOut();
			}, 2000);
		});
	};
	return Common;


//	(String.prototype.getQueryKey = Common.getQueryKey)();
})



