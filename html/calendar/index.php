<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('calendar/calendar');


	/**
	*	Custom body class
	*/
	$bodyclass = array('calendar');

	/**
	 *	JS modules
	 */
	$jsModules = array('calendar');

	include('../common/_header.php');
?>
		<!-- PAGE CONTENT -->
	<div class="flow">
		<div class="col-12">
			<ul id="centers" class="list-inline">
				<li class="menu-A63600 current"><a data-center="4" href="/c/1381856400/center/4">Центр физического развития</a></li>
				<li class="menu-05316D"><a data-center="7" href="/c/1381856400/center/7">Центр знаний</a></li>
				<li class="menu-053233"><a data-center="9" href="/c/1381856400/center/9">Ресторан</a></li>
			</ul>
			<ul id="services" class="list-inline">
				<li>
					<span class="item-ff5114">
						<i>Боевые искусства</i>
							<ul>
								<li data-service="2">Все направления</li>
								<li data-id="32">Рукопашный бой </li>
								<li data-id="65">Капоэйра</li>
								<li data-id="54">Прикладной рукопашный бой </li>
								<li data-id="36">Капоэйра для детей</li>
								<li data-id="34">Айкидо для детей</li>
								<li data-id="37">Кёкусинкай каратэ</li>
								<li data-id="66">Айкидо</li>
								<li data-id="67">Айкидо для семьи</li>
								<li data-id="42">Тайцзицюань</li>
							</ul>
						</span>
				</li>
				<li><span class="item-ffa12d"><i>Йога</i></span></li>
				<li><span class="item-ffbd03"><i>Танцы</i></span></li>
				<li><span class="item-ff8947"><i>Гимнастика</i></span></li>
				<li><span class="item-ffa88d"><i>Мамина школа</i></span></li>
				<li><span class="item-ff795f"><i>Медитации</i></span></li>
				<li><span class="item-e06104"><i>Гармонизация звуком</i></span></li>
			</ul>
			<ul id="filter" class="list-inline">
				<li><span>Гармонизация звуком (тибетские чаши)</span><i></i></li>
			</ul>
			<div id="period" class="flow">
				<div class="col-6">
					<a class="prev" data-time="1377968400" href="/c/1377968400/center/4">Сентябрь</a>
					<strong class="current" data-month="10" data-year="2013">Октябрь, 2013</strong>
					<a class="next" data-time="1383238800" href="/c/1383238800/center/4">Ноябрь</a>
				</div>
				<div class="col-6 text-right">
					<a class="prev" data-time="1381078800" href="/c/1381078800/center/4">Предыдущая неделя</a>
					<a class="next" data-time="1382288400" href="/c/1382288400/center/4">Следующая неделя</a>
				</div>
			</div>
			<div class="scroller">
				<ul id="days" class="list-inline list-justified"></ul>
			</div>
			<div class="scroller">
				<div id="events">
					<div class="timeline">
						<div><span>07<sup>00</sup></span></div>
						<div><span>08<sup>00</sup></span></div>
						<div><span>09<sup>00</sup></span></div>
						<div><span>10<sup>00</sup></span></div>
						<div><span>11<sup>00</sup></span></div>
						<div><span>12<sup>00</sup></span></div>
						<div><span>13<sup>00</sup></span></div>
						<div><span>14<sup>00</sup></span></div>
						<div><span>15<sup>00</sup></span></div>
						<div><span>16<sup>00</sup></span></div>
						<div><span>17<sup>00</sup></span></div>
						<div><span>18<sup>00</sup></span></div>
						<div><span>19<sup>00</sup></span></div>
						<div><span>20<sup>00</sup></span></div>
						<div><span>21<sup>00</sup></span></div>
					</div>
					<div class="events-wrapper">
						<div class="events-wrapper">
							<div>
								<div class="text-center">Энергия Земли</div>
								<div class="row timeline-row">
									<div data-event="1367"
									     class="col-90 start-02-00 c-ff5114"><span>Рукопашный бой </span><span>02:00 — 03:30</span>
									</div>
									<div data-event="1415"
									     class="col-90 start-11-00 c-ff5114"><span>Капоэйра для детей</span><span>11:00 — 12:30</span>
									</div>
									<div data-event="956"
									     class="col-90 start-11-00 c-ff5114"><span>Капоэйра для детей</span><span>11:00 — 12:30</span>
									</div>
									<div data-event="912"
									     class="col-90 start-14-00 c-ffa12d"><span>Хатха</span><span>14:00 — 15:30</span>
									</div>
									<div data-event="916"
									     class="col-90 start-15-30 c-ff5114"><span>Капоэйра</span><span>15:30 — 17:00</span>
									</div>
								</div>
							</div>
							<div>
								<div class="text-center">Энергия Воды</div>
								<div class="row timeline-row">
									<div data-event="920"
									     class="col-90 start-02-00 c-ffa12d"><span>Кундалини-йога</span><span>02:00 — 03:30</span>
									</div>
									<div data-event="924"
									     class="col-90 start-12-30 c-ffa12d"><span>Йога 23</span><span>12:30 — 14:00</span>
									</div>
								</div>
							</div>
							<div>
								<div class="text-center">Энергия Солнца</div>
								<div class="row timeline-row">
									<div data-event="928"
									     class="col-90 start-03-30 c-ffa12d"><span>Аштанга-виньяса йога</span><span>03:30 — 05:00</span>
									</div>
									<div data-event="932"
									     class="col-60 start-07-00 c-ffa12d"><span>Нидра-йога</span><span>07:00 — 08:00</span>
									</div>
									<div data-event="936"
									     class="col-90 start-11-00 c-ffbd03"><span>Танцевальное направление Ragga/Dancehall </span><span>11:00 — 12:30</span>
									</div>
									<div data-event="940"
									     class="col-120 start-15-00 c-ff5114"><span>Прикладной рукопашный бой </span><span>15:00 — 17:00</span>
									</div>
								</div>
							</div>
							<div>
								<div class="text-center">Энергия Света</div>
								<div class="row timeline-row">
									<div data-event="944"
									     class="col-90 start-12-30 c-ff8947"><span>Гимнастика для лица</span><span>12:30 — 14:00</span>
									</div>
								</div>
							</div>
							<div>
								<div class="text-center">Энергия Ветра</div>
								<div class="row timeline-row">
									<div data-event="948"
									     class="col-60 start-05-00 c-ffbd03"><span>Арабский терапевтический  танец для детей</span><span>05:00 — 06:00</span>
									</div>
									<div data-event="952"
									     class="col-90 start-14-00 c-ffa12d"><span>Хатха для начинающих  </span><span>14:00 — 15:30</span>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- EOF PAGE CONTENT -->
<?php
	include('../common/_footer.php');
?>