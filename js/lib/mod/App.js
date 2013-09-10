// Definition
lib.module('mod.App');

// Dependencies
lib.include('plugins.Tooltip');

// Class definition
var App = function () { 'use strict';

	// Private method
	function _start () {
		console.log(this);
	};

	// Public method
	function initialize () {
		//_start.call(this);
		$('.starter-template').tooltip({
			selector: '[data-toggle=tooltip]',
			container: 'body'
		});
		console.log(this);
	}

	// Mapping
	return {
		initialize:initialize
	};
}();
