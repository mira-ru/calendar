<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('calendar');


	/**
	*	Custom body class
	*/
	$bodyclass = array('calendar', 'physical');

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
						<!-- <li>Хатха-йога для начинающих<i data-show="1"></i></li> -->
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<a class="prev-month" data-time="1377986400" href="/c/4/0/0/1377986400">Сентябрь</a><strong class="current" data-month="10" data-year="2013">Октябрь, 2013</strong><a class="next-month" data-time="1383260400" href="/c/4/0/0/1383260400">Ноябрь</a>		</div>
			</div>
			<div class="table-responsive first-table">
				<table class="table timeline-days">
					<thead>
					<tr>
						<td><span class=" disabled"><i data-weekday="вт" data-day="1380578400">1</i></span></td><td><span class=" disabled"><i data-weekday="ср" data-day="1380664800">2</i></span></td><td><span class=" disabled"><i data-weekday="чт" data-day="1380751200">3</i></span></td><td><span class=" disabled"><i data-weekday="пт" data-day="1380837600">4</i></span></td><td><span class=" disabled"><i data-weekday="сб" data-day="1380924000" class="weekend">5</i></span></td><td><span class=" disabled"><i data-weekday="вс" data-day="1381010400" class="weekend">6</i></span></td><td><span class="current"><i data-weekday="пн" data-day="1381096800">7</i></span></td><td><span class=""><i data-weekday="вт" data-day="1381183200">8</i></span></td><td><span class=""><i data-weekday="ср" data-day="1381269600">9</i></span></td><td><span class=""><i data-weekday="чт" data-day="1381356000">10</i></span></td><td><span class=""><i data-weekday="пт" data-day="1381442400">11</i></span></td><td><span class=""><i data-weekday="сб" data-day="1381528800" class="weekend">12</i></span></td><td><span class=""><i data-weekday="вс" data-day="1381615200" class="weekend">13</i></span></td><td><span class=""><i data-weekday="пн" data-day="1381701600">14</i></span></td><td><span class=""><i data-weekday="вт" data-day="1381788000">15</i></span></td><td><span class=""><i data-weekday="ср" data-day="1381874400">16</i></span></td><td><span class=""><i data-weekday="чт" data-day="1381960800">17</i></span></td><td><span class=""><i data-weekday="пт" data-day="1382047200">18</i></span></td><td><span class=""><i data-weekday="сб" data-day="1382133600" class="weekend">19</i></span></td><td><span class=""><i data-weekday="вс" data-day="1382220000" class="weekend">20</i></span></td><td><span class=""><i data-weekday="пн" data-day="1382306400">21</i></span></td><td><span class=""><i data-weekday="вт" data-day="1382392800">22</i></span></td><td><span class=""><i data-weekday="ср" data-day="1382479200">23</i></span></td><td><span class=""><i data-weekday="чт" data-day="1382565600">24</i></span></td><td><span class=""><i data-weekday="пт" data-day="1382652000">25</i></span></td><td><span class=""><i data-weekday="сб" data-day="1382738400" class="weekend">26</i></span></td><td><span class=""><i data-weekday="вс" data-day="1382824800" class="weekend">27</i></span></td><td><span class=" disabled"><i data-weekday="вс" data-day="1382911200" class="weekend">28</i></span></td><td><span class=" disabled"><i data-weekday="пн" data-day="1382997600">29</i></span></td><td><span class=" disabled"><i data-weekday="вт" data-day="1383084000">30</i></span></td><td><span class=" disabled"><i data-weekday="ср" data-day="1383170400">31</i></span></td>				</tr>
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
								<div><div class="text-center">Энергия Земли</div><div class="row timeline-row"><div data-sub="32" data-event="780" data-sid="2" class="col-210 start-02-00 c-ff5114"><span>Рукопашный бой </span></div><div data-sub="36" data-event="832" data-sid="2" class="col-90 start-11-00 c-ff5114"><span>Капоэйра для детей</span></div><div data-sub="38" data-event="784" data-sid="30" class="col-90 start-14-00 c-ffa12d"><span>Хатха</span></div><div data-sub="65" data-event="788" data-sid="2" class="col-90 start-15-30 c-ff5114"><span>Капоэйра</span></div></div></div><div><div class="text-center">Энергия Воды</div><div class="row timeline-row"><div data-sub="39" data-event="792" data-sid="30" class="col-90 start-02-00 c-ffa12d"><span>Кундалини-йога</span></div><div data-sub="43" data-event="796" data-sid="30" class="col-90 start-12-30 c-ffa12d"><span>Йога 23</span></div></div></div><div><div class="text-center">Энергия Солнца</div><div class="row timeline-row"><div data-sub="45" data-event="800" data-sid="30" class="col-90 start-03-30 c-ffa12d"><span>Хатха йога с элементами аштанга йоги</span></div><div data-sub="46" data-event="804" data-sid="30" class="col-60 start-07-00 c-ffa12d"><span>Нидра-йога</span></div><div data-sub="50" data-event="808" data-sid="33" class="col-60 start-11-00 c-ffbd03"><span>Танцевальное направление Ragga/Dancehall </span></div><div data-sub="54" data-event="812" data-sid="2" class="col-90 start-15-00 c-ff5114"><span>Прикладной рукопашный бой </span></div></div></div><div><div class="text-center">Энергия Света</div><div class="row timeline-row"><div data-sub="55" data-event="816" data-sid="30" class="col-120 start-02-30 c-ffa12d"><span>Хатха-йога</span></div><div data-sub="62" data-event="820" data-sid="29" class="col-90 start-12-30 c-ff8947"><span>Гимнастика для лица</span></div></div></div><div><div class="text-center">Энергия Ветра</div><div class="row timeline-row"><div data-sub="70" data-event="824" data-sid="33" class="col-60 start-05-00 c-ffbd03"><span>Арабский терапевтический  танец для детей</span></div><div data-sub="40" data-event="828" data-sid="30" class="col-60 start-14-00 c-ffa12d"><span>Хатха для начинающих  </span></div></div></div>						</div>
							<p class="warning-empty">К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!</p>					</td>
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
	</div>
<style>

</style>
		<!-- EOF PAGE CONTENT -->
<?php
	include('../common/_footer.php');
?>