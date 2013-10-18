<?php
/**
 * @var $model
 * @var $centers array
 * @var $centerId Center
 * @var $currentTime integer
 *
 * @var $events array
 * @var $halls array
 * @var $services array
 * @var $activeDays array
 * @var allServices array
 *
 * @var $currentMonth integer
 * @var $nextMonth integer
 * @var $serviceId integer
 * @var $directionId integer
 * @var $userId integer
 * @var $hallId integer
 */

// layout settings
$this->layout = '//layouts/front';
$this->pageTitle = 'Расписание';
$this->moduleId = array('Calendar');
$this->bodyClass = array('calendar');

?>
<!-- PAGE CONTENT -->
<script>
	//Calendar.reloadWithHash();
</script>
<div id="wrap" class="grid <?php echo ( Config::getIsWeekView($model) ) ? 'week-view' : '';?>">
	<div id="header" class="grid">
		<div class="flow align-middle">
			<div class="col-2" id="logo"><a href="http://miracentr.ru"><img src="/images/logo.png" class="_img-responsible"></a></div>
			<div class="col-3" id="name"><h1>Расписание</h1></div>
			<div class="col-5" id="search">
				<div><input type="text" class="form-control" value="<?php echo empty($_GET['search']) ? '' : addslashes($_GET['search']); ?>"><i></i></div>
			</div>
			<div class="col-2 text-right" id="phone">
				<span>Запись по телефону:</span>
				<h2>2-300-108</h2>
			</div>
		</div>
	</div>
	<div class="flow">
		<div class="col-12">
			<ul id="centers" class="list-inline">
				<?php
					/** @var $center Center */
					foreach ($centers as $center) {
						$class = 'menu-'.ltrim($center->color, '#');

						if ($center->id == $centerId) {
							$class .= ' current';
						}
						$url = $this->createUrl('/site/index', array('class_id'=>Center::MODEL_TYPE, 'id'=>$center->id, 'time'=>$currentTime));
						echo CHtml::tag('li', array('class'=>$class),
							CHtml::link($center->name, $url, array('data-center' => $center->id))
						)."\n";
					}
				?>				
			</ul>
			<ul id="services" class="list-inline">
				<?php
					/** @var $service Service */
					foreach ($services as $service) {
						echo CHtml::openTag('li');

							$class = 'item-'.ltrim($service->color, '#');
							echo CHtml::openTag('span', array('class'=>$class));
								echo '<i>'.$service->name."</i>\n";

								echo CHtml::openTag('ul');
								echo CHtml::tag('li', array('data-service'=>$service->id), 'Все направления')."\n";

								/** @var $direction Direction */
								foreach (Direction::getActiveByTime($currentMonth, $nextMonth, $service->id) as $direction) {
									echo CHtml::tag('li', array('data-id'=>$direction->id), $direction->name)."\n";
								}

								echo CHtml::closeTag('ul');
							echo CHtml::closeTag('span');

						echo CHtml::closeTag('li')."\n";
					}
				?>
			</ul>
			<ul id="filter" class="list-inline">
				<?php
					if ( $model instanceof Service ) {
						echo CHtml::tag('li', array(), $model->name.' (все направления)<i></i>');
					} elseif ( $model instanceof Direction ) {
						echo CHtml::tag('li', array(), $model->name.'<i></i>');
					}
				?>
			</ul>
			<div id="period" class="flow">
				<div class="col-6">
					<?php
						$monthNumber = date('n', $currentTime);
						$yearNumber = date('Y', $currentTime);
						$prevMonthTime = DateMap::prevMonth($currentTime);
						echo CHtml::link(DateMap::$monthMap[ date('n', $prevMonthTime) ],
							$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$prevMonthTime)),
							array('class'=>'prev', 'data-time'=>$prevMonthTime)
						);
						echo CHtml::tag('strong',
							array('class'=>'current', 'data-month'=>$monthNumber, 'data-year'=>$yearNumber),
							DateMap::$monthMap[$monthNumber].', '.$yearNumber
						);
						echo CHtml::link(DateMap::$monthMap[ date('n', $nextMonth) ],
							$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$nextMonth)),
							array('class'=>'next', 'data-time'=>$nextMonth)
						);
					?>
				</div>
				<div class="col-6 text-right">
					<?php
						$prevWeek = DateMap::prevWeek($currentTime);
						$nextWeek = DateMap::nextWeek($currentTime);
						echo CHtml::link('Предыдущая неделя',
							$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$prevWeek)),
							array('class'=>'prev', 'data-time'=>$prevWeek)
						);

						echo CHtml::link('Следующая неделя',
							$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$nextWeek)),
							array('class'=>'next', 'data-time'=>$nextWeek)
						);
					?>
				</div>
			</div>
			<div class="scroller">
				<ul id="days" class="list-inline list-justified">
					<?php
						// выбрано направление - недельный вид
						if ( Config::getIsWeekView($model) ) {
							$this->renderPartial('index/_daysWeek',
								array('currentTime'=>$currentTime, 'activeDays'=>$activeDays)
							);
						} else {
							$this->renderPartial('index/_daysMonth',
								array('currentTime'=>$currentTime, 'activeDays'=>$activeDays)
							);
						}
					?>
				</ul>
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
						<?php
							// выбрано направление - недельный вид
							if ( Config::getIsWeekView($model) ) {
								$this->renderPartial('index/_weekEvents', array(
									'halls'=>$halls,
									'events'=>$events,
									'services'=>$allServices,
									'currentTime'=>$currentTime,
								));
							} else {
								$this->renderPartial('index/_monthEvents', array(
									'halls'=>$halls,
									'events'=>$events,
									'services'=>$services,
								));
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- EOF PAGE CONTENT -->
<script>
	var defaultState = <?php echo json_encode(array(
		'center_id'=>$centerId,
		'type' => Config::$routeMap[$model::MODEL_TYPE],
		'item' => $model->id,
		'day'=>$currentTime,
		'search'=>!empty($_GET['search']),
	), JSON_NUMERIC_CHECK); ?>;
</script>