<?php
/* @var $this FeedbackController */

$this->breadcrumbs=array(
	'Feedback',
);
?>


<div class="grid">
	<div class="flow">
		<div class="col-6 skip-3 pass-3">
			<div class="feedback-form">
				<h1 class="title">Книга отзывов и предложений</h1>
				<div id="form">
					<form id="feedbackForm">
						<label class="radio"><input type="radio" value="0" name="status"><span class="icon-lightbulb">я хочу предложить</span></label>
						<label class="radio"><input type="radio" value="1" name="status"><span class="icon-heart-outline">я хочу поблагодарить</span></label>
						<label class="radio"><input type="radio" value="2" name="status"><span class="icon-warning-outline">я хочу пожаловаться</span></label>

						<label>
							<!-- <span class="block">следующее:</span> -->
							<textarea rows="10" placeholder="Написать что-нибудь очень желательно" name="text"></textarea>
						</label>
						<label>
							<span>Меня зовут:</span>
							<input type="text" name="name" placeholder="Можно не представляться">
						</label>
						<div class="submit">
							<button type="submit">Отправить</button>
						</div>
					</form>
				</div>
				<div id="tabs">
					<a data-status=0 href="#"><i>Предложения</i></a>
					<a data-status=1 href="#" class="current"><i>Благодарности</i></a>
					<a data-status=2 href="#"><i>Жалобы</i></a>
				</div>
				<div id="list">
					<div>
						<h1>Аноним <span class="red">жалуется</span>:</h1>
						<p>«Текст»</p>
					</div>
					<div>
						<h1>logonarium <span class="orange">предлагает</span>:</h1>
						<p>«Текст»</p>
					</div>
					<div>
						<h1>Аноним <span class="green">благодарит</span>:</h1>
						<p>«Текст»</p>
					</div>
				</div>
				<div id="tabs"></div>
				<div id="list"></div>
			</div>
		</div>
	</div>
</div>
<script id="feedbackList" type="text/mustache">
	{{#items}}
		<div><h1>{{name}}:</h1><p>«{{text}}»</p></div>
	{{/items}}
</script>
<script id="tabsView" type="text/ejs">
	<a data-type="neutral" class="current"><i>Предложения</i></a>
	<a data-type="positive"><i>Благодарности</i></a>
	<a data-type="negative"><i>Жалобы</i></a>	
</script>
<script>
(function(){

	$('.radio').on('click', function(){
		$('.hidden').slideDown('400');
	});

	var Feedback = can.Map({
		defaults: {
			type: 'neutral'
		}
	},{
		type: function(newVal) {
			this.attr('type', newVal);
		}
	});

	var AppControl = can.Control.extend({
		init: function(){
			var app = this;
			var state = this.options.state = new Feedback();

			new Tabs('#tabs', {state: state});

			var items = can.compute(function(){
				var params = {
					type: state.attr('type')
				};
				var feeds = Feed.findAll(params);
				return feeds;
			});
			
			new List("#list", {
				items: items,
				template: "feedbackList"
			});
			
			this.on();
		},
		'{state} type': function(state) {
			can.route.attr('type', state.attr('type'));
		},
		'{can.route} type': function(route) {
			this.options.state.type(route.attr('type'));
		}
	});

	var Tabs = can.Control.extend({
		init: function(){
			this.element.html(
				can.view('#tabsView'),
				this.options
			);
		},
		'a click': function(el, ev){
			ev.preventDefault();
			var state = this.options.state;
			state.attr('type', el.data('type'));
			el.siblings('.current').andSelf().toggleClass('current');
			$('.hidden').slideUp('400', function(){
				$('input:checked').removeAttr('checked');
			});
		}
	});

	var Feed = can.Model.extend({
		findAll: 'GET /feedback/axFeedback',
		findOne: 'GET /feedback/axFeedback/{id}',
		create:  'POST /feedback/axFeedback',
		update:  'PUT /feedback/axFeedback/{id}',
		destroy: 'DELETE /feedback/axFeedback/{id}'
	},{});

	var store = can.fixture.store(15, function(i) {
		return {
			id : i,
			type: 'neutral',
			name : 'Anonym ' + i,
			text : 'Olololo ' + i
		}
	});

	can.fixture.delay = 3000;

	can.fixture('GET /feedback/axFeedback', store.findAll);

	// can.fixture('GET /feedback/axFeedback', function() {
	// 	return [
	// 		{id: 1, type: 'neutral', name: 'Anonym', text: 'Ololo'}
	// 	];
	// });

	var List = can.Control.extend({
		init: function(){
			this.update();
		},
		'{items} change': 'update',
		update: function(){
			var items = this.options.items();
			// if (can.isDeferred( items )) {
			// 	this.element.find('tbody').css('opacity', 0.5)
			// 	items.then(this.proxy('draw'));
			// } else {
				this.draw(items);
			// }
		},
		draw: function(items){
			// this.element.find('tbody').css('opacity', 1);
			var data = $.extend({}, this.options, {items: items})
			this.element.html( can.view(this.options.template, data) );
			console.log(items);
		}
	});

	new AppControl(document.body);

	can.route(':type');

	// can.route('', {type: 'neutral'});

	can.route.ready();
})();
</script>
