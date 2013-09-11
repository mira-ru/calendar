<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('starter-template');


	/**
	*	Custom body class
	*/
	$bodyclass = array('calendar'); 

	include('../common/_header.php');
?>
		<!-- PAGE CONTENT -->
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-8">
<!-- 					<a class="btn btn-default" href="javascript:void(0);" data-selector="all">Весь день</a>
					<a class="btn btn-success" href="javascript:void(0);" data-selector="m">Утро</a>
					<a class="btn btn-info" href="javascript:void(0);" data-selector="d">День</a>
					<a class="btn btn-warning" href="javascript:void(0);" data-selector="e">Вечер</a> -->

					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default active">
							<input type="radio" name="options" id="all"> Весь день
						</label>
						<label class="btn btn-info">
							<input type="radio" name="options" id="m"> Утро
						</label>
						<label class="btn btn-success">
							<input type="radio" name="options" id="d"> День
						</label>
						<label class="btn btn-warning">
							<input type="radio" name="options" id="e"> Вечер
						</label>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<td class="m">07:00</td>
							<td class="m">08:00</td>
							<td class="m">09:00</td>
							<td class="m">10:00</td>
							<td class="m">11:00</td>
							<td class="d">12:00</td>
							<td class="d">13:00</td>
							<td class="d">14:00</td>
							<td class="d">15:00</td>
							<td class="d">16:00</td>
							<td class="e">17:00</td>
							<td class="e">18:00</td>
							<td class="e">19:00</td>
							<td class="e">20:00</td>
							<td class="e">21:00</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="m">07:00</td>
							<td class="m">08:00</td>
							<td class="m">09:00</td>
							<td class="m">10:00</td>
							<td class="m">11:00</td>
							<td class="d">12:00</td>
							<td class="d">13:00</td>
							<td class="d">14:00</td>
							<td class="d">15:00</td>
							<td class="d">16:00</td>
							<td class="e">17:00</td>
							<td class="e">18:00</td>
							<td class="e">19:00</td>
							<td class="e">20:00</td>
							<td class="e">21:00</td>
						</tr>
					</tbody>
				</table>				
			</div>
		</div>
		<!-- EOF PAGE CONTENT -->
	<script>
		$(function () {
			//App.initialize();

			lib.include('plugins.bootstrap.Button');
			
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
			})
		});
	</script>
<?php
	include('../common/_footer.php');
?>