<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('calendar');


	/**
	*	Custom body class
	*/
	$bodyclass = array('calendar', '');

	/**
	 *	Module ID
	 */
	$moduleId = array('Calendar');

	include('../common/_header.php');
?>
		<!-- PAGE CONTENT -->
	<div id="wrap" class="week-view">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1>Расписание</h1>
				</div>
				<div class="col-lg-12">
					<ul class="list-inline list-justified top-menu">
						<li class="item-1 current"><a data-center="4" href="javascript:void(0)">Центр физического развития</a></li>
						<li class="item-2"><a data-center="6" href="/c/6/0/0/1381096800">Творческий центр</a></li>
						<li class="item-3"><a data-center="7" href="/c/7/0/0/1381096800">Центр знаний</a></li>
					</ul>
					<ul class="list-inline list-justified sub-menu">

						<li><span class="item-ff5114"><i>Боевые искусства</i>
							<ul class="list-unstyled"><li data-service="2">Все направления</li>
								<li data-id="32">Рукопашный бой </li>
								<li data-id="65">Капоэйра</li>
								<li data-id="54">Прикладной рукопашный бой </li>
								<li data-id="36">Капоэйра для детей</li>
								<li data-id="34">Айкидо для детей</li>
								<li data-id="37">Кёкусинкай каратэ</li>
								<li data-id="66">Айкидо</li>
								<li data-id="67">Айкидо для семьи</li>
								<li data-id="42">Тайцзицюань</li>
							</ul></span></li>
													<li><span class="item-ffa12d"><i>Йога</i>
							<ul class="list-unstyled"><li data-service="30">Все направления</li>
								<li data-id="38">Хатха</li>
								<li data-id="39">Кундалини-йога</li>
								<li data-id="43">Йога 23</li>
								<li data-id="45">Хатха йога с элементами аштанга йоги</li>
								<li data-id="46">Нидра-йога</li>
								<li data-id="55">Хатха-йога</li>
								<li data-id="40">Хатха для начинающих  </li>
								<li data-id="33">Аэро-йога</li>
								<li data-id="35"> Айенгара-йога </li>
								<li data-id="41">Ишвара-йога</li>
								<li data-id="53">Анахатха йога</li>
								<li data-id="57">Йога для детей</li>
								<li data-id="68">Йога для беременных</li>
								<li data-id="44">Женская йога </li>
								<li data-id="58">Шри-шри йога </li>
								<li data-id="59">Гималайская йога </li>
								<li data-id="48">Йога дервишей </li>
							</ul></span></li>
													<li><span class="item-ffbd03"><i>Танцы</i>
							<ul class="list-unstyled"><li data-service="33">Все направления</li>
								<li data-id="50">Танцевальное направление Ragga/Dancehall </li>
								<li data-id="70">Арабский терапевтический  танец для детей</li>
								<li data-id="49">Фламенко</li>
								<li data-id="51">Арабский терапевтический танец (Мама+дочь)</li>
								<li data-id="52">АТС (АмериканТрайблСтайл)</li>
								<li data-id="64">Арабский терапевтический  танец</li>
								<li data-id="63"> Танец мандала </li>
								<li data-id="47">Гурджиевские танцы</li>
							</ul></span></li>
													<li><span class="item-ff8947"><i>Гимнастика</i>
							<ul class="list-unstyled"><li data-service="29">Все направления</li>
								<li data-id="62">Гимнастика для лица</li>
								<li data-id="56">Дыхательная суставная гимнастика </li>
							</ul></span></li>
													<li><span class="item-ffa88d"><i>Мамина школа</i>
							<ul class="list-unstyled"><li data-service="31">Все направления</li>
								<li data-id="61"> baby-йога</li>
								<li data-id="60">Курсы по подготовке к беременности и ГВ </li>
							</ul></span></li>
													<li><span class="item-ff795f"><i>Медитации</i>
							<ul class="list-unstyled"><li data-service="34">Все направления</li>
								<li data-id="69">ошо медитация</li>
							</ul></span></li>
													<li><span class="item-e06104"><i>Гармонизация звуком</i>
							<ul class="list-unstyled"><li data-service="35">Все направления</li>
								<li data-id="71">Гармонизация звуком (тибетские чаши)</li>
							</ul></span></li>
					</ul>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<ul class="list-inline filter-items">
						<li><span>Рукопашный бой </span><i></i></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<a class="prev-month" data-time="1377986400"  href="#">Сентябрь</a>
					<strong class="current" data-month="10" data-year="2013">Октябрь,2013</strong>
					<a class="next-month" data-time="1383260400" href="#">Ноябрь</a>
				</div>
				<div class="col-lg-6 ">
					<a class="prev-month" data-time="1377986400"  href="#">Предыдущая неделя</a>
					<a class="next-month" data-time="1383260400" href="#">Следующая неделя</a>
				</div>
			</div>
			<div class="table-responsive first-table">
				<table class="table timeline-days">
					<thead>
					<tr>
						<td>
							<span class=""><i data-weekday="пн" data-day="1381096800">7</i></span>
						</td>
						<td>
							<span class=""><i data-weekday="вт" data-day="1381183200">8</i></span>
						</td>
						<td>
							<span class="current"><i data-weekday="ср" data-day="1381269600">9</i></span>
						</td>
						<td>
							<span class=""><i data-weekday="чт" data-day="1381356000">10</i></span>
						</td>
						<td>
							<span class=""><i data-weekday="пт" data-day="1381442400">11</i></span>
						</td>
						<td>
							<span class=""><i data-weekday="сб" data-day="1381528800" class="weekend">12</i></span>
						</td>
						<td>
							<span class=""><i data-weekday="вс" data-day="1381615200" class="weekend">13</i></span>
						</td>
					</tr>
					</thead>
				</table>
			</div>
			<div class="table-responsive second-table">
				<table class="table timeline-hours">
					<tbody>
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
					<tr>
						<td colspan="15" class="timeline-wrapper">
							<div>
								<div class="row  timeline-row">
									<div class="col-150 empty"></div>
									<div class="col-150 c-ff5114">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="36" data-event="832"
									     data-sid="2"
									     class="col-150 c-ff5114">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
									<div data-sub="38" data-event="784"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="65" data-event="788"
									     data-sid="2"
									     class="col-150 c-ff5114">
										<span>07:30 — 09:30</span>
										<a href="#">Елена Журавлева</a>
									</div>
								</div>
								<div class="row timeline-row">
									<div data-sub="39" data-event="792"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="43" data-event="796"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
								<div class="row current timeline-row">
									<div data-sub="45" data-event="800"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="46" data-event="804"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
									<div data-sub="50" data-event="808"
									     data-sid="33"
									     class="col-150 c-ffbd03">
										<span>07:30 — 09:30</span>
										<a href="#">Елена Журавлева</a>
									</div>
									<div data-sub="54" data-event="812"
									     data-sid="2"
									     class="col-150 c-ff5114">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
								<div class="row timeline-row">
									<div data-sub="55" data-event="816"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="62" data-event="820"
									     data-sid="29"
									     class="col-150 c-ff8947">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
								<div class="row timeline-row">
									<div data-sub="70" data-event="824"
									     data-sid="33"
									     class="col-150 c-ffbd03">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="40" data-event="828"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
								<div class="row timeline-row">
									<div data-sub="55" data-event="816"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="62" data-event="820"
									     data-sid="29"
									     class="col-150 c-ff8947">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
								<div class="row timeline-row">
									<div data-sub="70" data-event="824"
									     data-sid="33"
									     class="col-150 c-ffbd03">
										<span>07:30 — 09:30</span>
										<a href="#">Павел Коноровский</a>
									</div>
									<div data-sub="40" data-event="828"
									     data-sid="30"
									     class="col-150 c-ffa12d">
										<span>07:30 — 09:30</span>
										<a href="#">Света Морозова</a>
									</div>
								</div>
							</div>
							<p class="warning-empty">К сожалению, в этот день нет занятий.
								Попробуйте выбрать другой день!</p></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="event-balloon">
			<div></div>
			<!-- <i class="cross"></i> -->
		</div>
	</div>
<style>

</style>
		<!-- EOF PAGE CONTENT -->
<?php
	include('../common/_footer.php');
?>