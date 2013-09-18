<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('calendar');


	/**
	*	Custom body class
	*/
	$bodyclass = array('calendar', 'creative');

	/**
	 *	Module ID
	 */
	$moduleId = array('Calendar');

	include('../common/_header.php');
?>
		<!-- PAGE CONTENT -->
		<div id="wrap">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<h1>Расписание</h1>
					</div>
					<div class="col-lg-12">
						<ul class="list-inline list-justified top-menu">
							<li class="item-1"><a href="index.php">Центр физического развития</a></li>
							<li class="item-2 current"><a href="#">Творческий центр</a></li>
							<li class="item-3"><a href="knowledge.php">Центр знаний</a></li>
						</ul>
						<ul class="list-inline list-justified sub-menu">
							<li>
								<span class="item-a">Школа рукоделия
									<ul class="list-unstyled">
										<li data-id="1">Декупаж</li>
										<li data-id="2">Бисероплетение</li>
										<li data-id="3">Валяние игрушек</li>
										<li data-id="4">Роспись по ткани</li>
										<li data-id="5">Лепка пластикой</li>
										<li data-id="6">Славянская (обережная) кукла</li>
										<li data-id="7">Витраж</li>
										<li data-id="8">Роспись по стеклу</li>
										<li data-id="0">Все направления</li>
									</ul>
								</span>
							</li>
							<li>
								<span class="item-b">Интуитивная живопись
									<ul class="list-unstyled">
										<li data-id="9">Мехенди</li>
										<li data-id="10">Правополушарное рисование</li>
										<li data-id="11">Рисование мандалы</li>
										<li data-id="12">Изо для детей</li>
										<li data-id="13">Изо-студия художественной импровизации</li>
										<li data-id="14">Художественные культуры народов мира</li>
										<li data-id="0">Все направления</li>
									</ul>
								</span>
							</li>
							<li>
								<span class="item-c">Театр
									<ul class="list-unstyled">
										<li data-id="15">Театр импровизации</li>
										<li data-id="16">Студия театральных экспериментов</li>
										<li data-id="0">Все направления</li>
									</ul>
								</span>
							</li>
							<li>
								<span class="item-d">Голосовые практики
									<ul class="list-unstyled">
										<li data-id="17">Обертонное пение</li>
										<li data-id="18">Горловое пение</li>
										<li data-id="19">Живопение</li>
										<li data-id="0">Все направления</li>
									</ul>
								</span>
							</li>
							<li>
								<span class="item-e">Этнические инструменты
									<ul class="list-unstyled">
										<li data-id="21">Детская йога от 3 до 6 лет</li>
										<li data-id="22">Беби-йога от 1,5 месяцев до 7 месяцев</li>
										<li data-id="23">Беби-йога от 7 месяцев до 2 лет</li>
										<li data-id="24">Детская йога от 3 до 10 лет</li>
										<li data-id="0">Все направления</li>
									</ul>
								</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<ul class="list-inline filter-items">
							<!-- <li>Хатха-йога для начинающих<i data-show="1"></i></li> -->
						</ul>
					</div>
				</div>
				<div class="table-responsive">
					<a href="#" class="prev-month">Октябрь</a><strong class="current" data-month="11" data-year="2013">Ноябрь, 2013</strong><a href="#" class="next-month">Декабрь</a>
					<table class="table timeline-days">
						<thead>
							<tr>
								<td><span><i data-weekday="пн" data-day="1">1</i></span></td>
								<td><span><i data-weekday="вт" data-day="2">2</i></span></td>
								<td><span><i data-weekday="ср" data-day="3">3</i></span></td>
								<td><span class="current"><i data-weekday="чт" data-day="4">4</i></span></td>
								<td><span><i data-weekday="пт" data-day="5">5</i></span></td>
								<td><span><i data-weekday="сб" data-day="6" class="weekend">6</i></span></td>
								<td><span><i data-weekday="вс" data-day="7" class="weekend">7</i></span></td>
								<td><span><i data-weekday="пн" data-day="8">8</i></span></td>
								<td><span><i data-weekday="вт" data-day="9">9</i></span></td>
								<td><span><i data-weekday="ср" data-day="10">10</i></span></td>
								<td><span><i data-weekday="чт" data-day="11">11</i></span></td>
								<td><span><i data-weekday="пт" data-day="12">12</i></span></td>
								<td><span><i data-weekday="сб" data-day="13" class="weekend">13</i></span></td>
								<td><span><i data-weekday="вс" data-day="14" class="weekend">14</i></span></td>
								<td><span><i data-weekday="пн" data-day="15">15</i></span></td>
								<td><span><i data-weekday="вт" data-day="16">16</i></span></td>
								<td><span><i data-weekday="ср" data-day="17">17</i></span></td>
								<td><span><i data-weekday="чт" data-day="18">18</i></span></td>
								<td><span><i data-weekday="пт" data-day="19">19</i></span></td>
								<td><span><i data-weekday="сб" data-day="20" class="weekend">20</i></span></td>
								<td><span><i data-weekday="вс" data-day="21" class="weekend">21</i></span></td>
								<td><span><i data-weekday="пн" data-day="22">22</i></span></td>
								<td><span><i data-weekday="вт" data-day="23">23</i></span></td>
								<td><span><i data-weekday="ср" data-day="24">24</i></span></td>
								<td><span><i data-weekday="чт" data-day="25">25</i></span></td>
								<td><span><i data-weekday="пт" data-day="26">26</i></span></td>
								<td><span><i data-weekday="сб" data-day="27" class="weekend">27</i></span></td>
								<td><span><i data-weekday="вс" data-day="28" class="weekend">28</i></span></td>
								<td><span><i data-weekday="пн" data-day="29">29</i></span></td>
								<td><span><i data-weekday="вт" data-day="30">30</i></span></td>
								<td><span><i data-weekday="ср" data-day="31">31</i></span></td>
							</tr>
						</thead>
					</table>
					<table class="table timeline-hours">
						<thead>
							<tr>
								<td><span>07<sup>00</sup></span></td>
								<td><span>08<sup>00</sup></span></td>
								<td><span>09<sup>00</sup></span></td>
								<td><span>10<sup>00</sup></span></td>
								<td><span>11<sup>00</sup></span></td>
								<td><span>12<sup>00</sup></span></td>
								<td><span>13<sup>00</sup></span></td>
								<td><span>14<sup>00</sup></span></td>
								<td><span>15<sup>00</sup></span></td>
								<td><span>16<sup>00</sup></span></td>
								<td><span>17<sup>00</sup></span></td>
								<td><span>18<sup>00</sup></span></td>
								<td><span>19<sup>00</sup></span></td>
								<td><span>20<sup>00</sup></span></td>
								<td><span>21<sup>00</sup></span></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="15" class="timeline-wrapper">
									<div>
										<div class="text-center">Зал «Энергия Земли»</div>
										<div class="row timeline-row">
											<div class="col-90 a" data-sub="1"><span>Рукопашный бой (Алексей&nbsp;Вербицкий)</span></div>
											<div class="col-90 start-08-30 b" data-sub="5"><span>Пилатес</span></div>
											<div class="col-90 start-10-00 a" data-sub="2"><span>Русское боевое искусство</span></div>
											<div class="col-90 start-16-00 a" data-sub="4"><span>Капоэйра для детей</span></div>
											<div class="col-90 start-17-30 a" data-sub="2"><span>Русское боевое искусство</span></div>
											<div class="col-90 start-19-00 c" data-sub="10"><span>Хатха для начинающих</span></div>
											<div class="col-90 start-20-30 a" data-sub="3"><span>Капоэйра (Екатерина&nbsp;Полетаева)</span></div>
										</div>
									</div>
									<div>
										<div class="text-center">Зал «Энергия Воды»</div>
										<div class="row timeline-row">
											<div class="col-90 c" data-sub="12"><span>Кундалини йога</span></div>
											<div class="col-90 start-17-30 c" data-sub="14"><span>Йога 23</span></div>
											<div class="col-90 start-19-00 c" data-sub="12"><span>Кундалини йога</span></div>
											<div class="col-90 start-20-30 c" data-sub="15"><span>Йога-фитнесс</span></div>
										</div>
									</div>
									<div>
										<div class="text-center">Зал «Энергия Огня»</div>
										<div class="row timeline-row">
											<div class="col-90 c" data-sub="11"><span>Хатха-йога (Силкачева&nbsp;Наталья)</span></div>
											<div class="col-90 start-14-00 a" data-sub="4"><span>Капоэйра для детей</span></div>
											<div class="col-90 start-16-30 a" data-sub="2"><span>Русское боевое искусство</span></div>
											<div class="col-120 start-18-00 d" data-sub="17"><span>Трайбл (Екатерина&nbsp;Поздняк)</span></div>
											<div class="col-120 start-20-00 d" data-sub="17"><span>Трайбл (Екатерина&nbsp;Поздняк)</span></div>
										</div>
									</div>
									<div>
										<div class="text-center">Зал «Энергия Солнца»</div>
										<div class="row timeline-row">
											<div class="col-90 start-11-00 b" data-sub="6"><span>Волновая гимнастика</span></div>
											<div class="col-120 start-14-00 d" data-sub="19"><span>Индийские танцы для детей 11-14&nbsp;лет</span></div>
											<div class="col-90 start-16-00 d" data-sub="20"><span>Танцы рагги для детей 6-9&nbsp;лет</span></div>
											<div class="col-90 start-19-00 c" data-sub="10"><span>Хатха для начинающих</span></div>
										</div>
									</div>
									<div>
										<div class="text-center">Зал «Энергия Ветра»</div>
										<div class="row timeline-row">
											<div class="col-60 c" data-sub="11"><span>Хатха-йога</span></div>
											<div class="col-60 start-12-00 e" data-sub="21"><span>Детская йога от 3 до 6 лет</span></div>
											<div class="col-90 start-13-00 e" data-sub="22"><span>Беби-йога от 1,5&nbsp;месяцев до 7&nbsp;месяцев</span></div>
											<div class="col-90 start-14-30 e" data-sub="23"><span>Беби-йога от 7&nbsp;месяцев до 2&nbsp;лет</span></div>
											<div class="col-60 start-16-00 e" data-sub="24"><span>Детская йога от 3 до 10&nbsp;лет</span></div>
											<div class="col-90 start-17-30 b" data-sub="7"><span>Гимнастика для лица</span></div>
											<div class="col-120 start-19-00 d" data-sub="18"><span>Танец мандала</span></div>
										</div>
									</div>
									<p class="warning-empty">К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!</p>
								</td>
							</tr>
						</tbody>
					</table>				
				</div>
			</div>
		</div>
		<!-- EOF PAGE CONTENT -->
<?php
	include('../common/_footer.php');
?>