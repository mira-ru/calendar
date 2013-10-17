var can = require("can"),
    utils = require('utils');

can.fixture("GET /days", function(){
	return [
		{day: 1, weekday: "пн", timestamp: 134655701},
		{day: 2, weekday: "вт", timestamp: 134655702},
		{day: 3, weekday: "ср", timestamp: 134655703},
		{day: 4, weekday: "чт", timestamp: 134655704, selected: true},
		{day: 5, weekday: "пт", timestamp: 134655705},
		{day: 6, weekday: "сб", timestamp: 134655706},
		{day: 7, weekday: "вс", timestamp: 134655707},
		{day: 8, weekday: "пн", timestamp: 134655708},
		{day: 9, weekday: "вт", timestamp: 134655709},
		{day: 10, weekday: "ср", timestamp: 134655710},
		{day: 11, weekday: "чт", timestamp: 134655711},
		{day: 12, weekday: "пт", timestamp: 134655712},
		{day: 13, weekday: "сб", timestamp: 134655713}
	];
});

can.fixture("GET /events", function(){
	return [
		{
			hall: 1, 
			hallName: 'Земля', 
			events: {
				1 : {name: 'one', service: 1, activity: 2, master: 3, length: 90}, 
				2 : {name: 'two', service: 2, activity: 3, master: 2, length: 60}
			}
		},
		{
			hall: 2, 
			hallName: 'Огонь', 
			events: {
				1 : {name: 'three', service: 1, activity: 2, master: 3, length: 90}, 
				2 : {name: 'four', service: 2, activity: 3, master: 2, length: 60}
			}
		},
	];
});

var Day = can.Model({
	findAll: 'GET /days'
}, {});

var Event = can.Model({
	findAll: 'GET /events'
}, {});

var Days = can.Control({
	init: function(el, options) {
		var self = this;
		Day.findAll({}, function(days) {
			self.element.html(can.view('daysList', days));
		});
	},
	'li:not(.active) click': function(li) {
		li.siblings('.active').andSelf().toggleClass('active').end().trigger('selected', li.data('timestamp'));
	}
});

var Events = can.Control({
	init: function(el, options) {
		var self = this;
		Event.findAll( {service: can.route.attr('service') }, function(events) {
			self.element.html(can.view('eventsList', events));
		});
	}
});

var Service = can.Control({
	init: function(el, options) {
		var self = this;
	},
	'li click': function(li) {
		can.route.attr({service: li.data('id')});
	}
});

var Routing = can.Control.extend({
	init : function(){
		new Days('#days');
		new Events('#events');
		new Service('#menu');
	},
	':service route': function(data) {
		console.log(this);
	},
	':timestamp route': function(data) {
		console.log(this);
	},
	':service/:timestamp route': function(data) {
		console.log(this);
	},
	'li selected': function(el, ev, timestamp) {
		can.route.attr({timestamp: timestamp});
	}
});

new Routing(document.body);