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
				<div class="form">
					<form id="form">
						<label class="radio"><input type="radio" value="1" name="type"><span class="icon-lightbulb">я хочу предложить</span></label>
						<label class="radio"><input type="radio" value="2" name="type"><span class="icon-heart-outline">я хочу поблагодарить</span></label>
						<label class="radio"><input type="radio" value="3" name="type"><span class="icon-warning-outline">я хочу пожаловаться</span></label>
						<div class="hidden">
							<label>
								<textarea rows="10" placeholder="Написать что-нибудь очень желательно" name="text"></textarea>
							</label>
							<label>
								<span>Меня зовут:</span>
								<input type="text" name="name" placeholder="Можно не представляться">
							</label>
							<div class="submit">
								<button type="submit">Отправить</button>
							</div>
						</div>
					</form>
				</div>
				<div id="tabs"></div>
				<div id="list"></div>
			</div>
		</div>
	</div>
</div>
<script id="listView" type="text/mustache">
	{{#items}}
		<div><h1>{{#if name}}{{name}}{{else}}Noname{{/name}}:</h1><p>«{{text}}»</p></div>
	{{/items}}
</script>
<script id="tabsView" type="text/ejs">
	<a data-type-id="1" class="<%= state.attr('type') == 1 ? 'current' : '' %>"><i>Предложения</i></a>
	<a data-type-id="2" class="<%= state.attr('type') == 2 ? 'current' : '' %>"><i>Благодарности</i></a>
	<a data-type-id="3" class="<%= state.attr('type') == 3 ? 'current' : '' %>"><i>Жалобы</i></a>	
</script>
<script>
(function(){

	$('.radio').on('click', function(){
		$('.hidden').slideDown('400');
	});

	can.route(':type', {type: 'neutral'}).ready();

	var types = ['', 'neutral', 'positive', 'negative'];

	var Feedback = can.Map.extend({}, {
		change: function(value) {
			this.attr('type', value);
		}
	});

	var Feed = can.Model.extend({
		findAll: 'GET /api/feedback',
		findOne: 'GET /api/feedback/{id}',
		create:  'POST /api/feedback',
		update:  'PUT /api/feedback/{id}',
		destroy: 'DELETE /api/feedback/{id}'
	},{});

	var AppControl = can.Control.extend({
		init: function() {
			var app = this;
			var state = app.options.state = new Feedback({
				type: $.inArray(can.route.attr('type'), types) || 1
			});

			new Form('#form', {state: state});

			new Tabs('#tabs', {state: state});

			var items = can.compute(function(){
				var params = {
					type: state.attr('type')
				};
				var feeds = Feed.findAll(params);
				feeds.then();
				return feeds;
			});

			new List('#list', {
				items: items,
				template: 'listView'
			});

			app.on();
		},
		'{state} type': function(state) {
			can.route.attr('type', types[state.attr('type')]);
		},
		'{can.route} type': function(route) {
			this.options.state.change($.inArray(route.attr('type'), types));
		}
	});

	var Form = can.Control.extend({
		'button click': function(){
			var self = this;
			var data = can.deparam(this.element.serialize());
			var feed = new Feed(data);
			feed.save(function(created){
				self.options.state.change(created.type);
				hideForm();
			});
			return false;
		}
	});

	var Tabs = can.Control.extend({
		init: function(el, options) {
			el.html(can.view('tabsView', options));
		},
		'a:not(.current) click': function(el, ev) {
			ev.preventDefault();
			var state = this.options.state;
			state.attr('type', el.data('type-id'));
			hideForm();
		}
	})

	var List = can.Control.extend({
		init: function(){
			this.update();
		},
		'{items} change': 'update',
		update: function(){
			var items = this.options.items();
			if (can.isDeferred( items )) {
				this.element.css('opacity', 0.5);
				items.then(this.proxy('draw'));
			} else {
				this.draw(items);
			}
		},
		draw: function(items){
			this.element.css('opacity', 1);
			var data = $.extend({}, this.options, {items: items})
			this.element.html( can.view(this.options.template, data) );
		}
	});

	function hideForm() {
		$('.hidden').slideUp('400', function(){
			$('input:checked').removeAttr('checked');
			$('input:not(:radio), textarea', $(this)).val('');
		});		
	}

	new AppControl(document.body);
})();
</script>
