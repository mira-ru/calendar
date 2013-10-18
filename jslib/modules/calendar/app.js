var	ui = require("UI"),
	calendar = require("calendar"),
	modal = require("modal"),
	common = require("common");

calendar.initialize();
var getStr = location.search;
String.prototype.getQueryKey = common.getQueryKey;
if (getStr.getQueryKey('m', true) || getStr.getQueryKey('a', true)) {
	var t = (getStr.getQueryKey('m', true)) ? 'm' : (getStr.getQueryKey('a', true)) ? 'a' : false;
	var v = (t == ('m' || 'a')) ? getStr.getQueryKey('m') : getStr.getQueryKey('a');
	var m = $('.modal'), getSubstr = '?type=' + t + '&item=' + v;
	m.modal({
		show: true,
		remote: '/site/axPopup' + getSubstr
	});
}
if (common.isMobile.any() != null) {
	$('body').addClass('touch');
}
$(document)
	.ajaxError(
	function (e, x, settings, exception) {
		var message;
		var statusErrorMap = {
			'400': "Server understood the request but request content was invalid.",
			'401': "Unauthorised access.",
			'404': "Not found",
			'403': "Forbidden resouce can't be accessed",
			'500': "Internal Server Error.",
			'503': "Service Unavailable"
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
		common.showError(message);
	});
