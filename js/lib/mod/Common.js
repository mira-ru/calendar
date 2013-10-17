lib.module('mod.Common');

var Common = function() {

	var isMobile = {
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

	function getQueryKey(key, presence) {
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
	}
	String.prototype.getQueryKey = getQueryKey;

	// function _key() {
	// 	var search = unescape(this.toString()), obj = {};
	// 	if (search != '') {
	// 		search = search.substr(1);
	// 		var params = search.split('&')

	// 		for (var i = 0; i < params.length; i++) { 
	// 			var pairs = params[i].split('='); 
	// 			obj[pairs[0]] = pairs[1];
	// 		}
	// 		return obj;

	// 	} else {
	// 		return obj ;
	// 	}
	// }
	// String.prototype.key = _key;

	function showError(msg) {
		$('div#error').html(msg).fadeIn(function(){
			var err = $(this);
			setTimeout(function(){
				err.fadeOut();
			}, 2000);
		});
	}

	return {
		isMobile:isMobile,
		showError:showError
	};
}();

$(function(){
	if (Common.isMobile.any() != null) {
		$('body').addClass('touch');
	}
	$(document)
		.ajaxError(
			function(e, x, settings, exception) {
				var message;
				var statusErrorMap = {
					'400' : "Server understood the request but request content was invalid.",
					'401' : "Unauthorised access.",
					'404' : "Not found",
					'403' : "Forbidden resouce can't be accessed",
					'500' : "Internal Server Error.",
					'503' : "Service Unavailable"
				};
				if (x.status) {
					message = statusErrorMap[x.status];
						if (!message) {
							message = "Unknow Error";
						}
				} else if (exception == 'parsererror') {
					message = "Parsing JSON Request failed";
				} else if (exception == 'timeout') {
					message = "Request Time out";
				} else if (exception == 'abort') {
					message = "Request was aborted by the server";
				} else {
					message = "Unknow Error";
				}
				Common.showError(message);
			});
});
