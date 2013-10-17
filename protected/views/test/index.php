<div id="wrap">
<div class="container">
	<ul id="menu">
		<li data-id="1">1</li>
		<li data-id="2">2</li>
	</ul>
	<div class="table-responsive first-table">
		<ul id="days" class="list-inline">
		</ul>
	</div>
	<div id="events"></div>
</div>
</div>

	<script type="text/ejs" id="daysList">
		<% this.each(function( day ) { %>
			<li data-timestamp="<%= day.attr('timestamp') %>" class="<%= day.attr('selected') ? 'current' : '' %>">
				<span>
					<i data-weekday="<%= day.attr('weekday') %>"><%= day.attr('day') %> (<%= day.attr('weekday') %>)</i>
				</span>
			</li>
		<% }) %>
	</script>
	<script type="text/ejs" id="eventsList">
		<% this.each(function( events ) { %>
			<div>
				<div class="text-center"><%= events.attr('hallName')%></div>
				<div class="row timeline-row">
				<% events.attr('events').each(function( ev ) { %>
					<div class="col-<%= ev.attr('length') %>"><span><%= ev.attr('name') %></span></div>
				<% }) %>
				</div>
			</div>
		<% }) %>
	</script>

	<script src="/jslib/index.lmd.js"></script>