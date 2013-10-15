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
	Calendar.reloadWithHash();
</script>
<div id="wrap" class="<?php echo ( Config::getIsWeekView($model) ) ? 'week-view' : '';?>">
<div class="container">
	<div class="row header">
		<div class="col-lg-2 col-md-3 col-xs-4 logo"><a href="http://miracentr.ru"><img src="/images/logo.png" class="img-responsible"></a></div>
		<div class="col-lg-4"><h1>Расписание</h1></div>
		<div class="col-lg-4 search-form">
			<div><input type="text" class="form-control" value="<?php echo empty($_GET['search']) ? '' : addslashes($_GET['search']); ?>"><i></i></div>
		</div>
		<div class="col-lg-2 text-right">
			<span>Запись по телефону:</span>
			<h2 class="green">2-300-108</h2>
		</div>
	</div>
	<div class="row">	
		<div class="col-lg-12">
			<ul class="list-inline list-justified top-menu">
				<?php
				/** @var $center Center */
				foreach ($centers as $center) {
					$class = 'menu-'.ltrim($center->color, '#');

					if ($center->id == $centerId) {
						$class .= ' current';
						$url = 'javascript:void(0)';
					} else {
						$url = $this->createUrl('/site/index', array('class_id'=>Center::MODEL_TYPE, 'id'=>$center->id, 'time'=>$currentTime));
					}
					echo CHtml::tag('li', array('class'=>$class),
						CHtml::link($center->name, $url, array('data-center' => $center->id))
					)."\n";
				}
				?>
			</ul>
			<ul class="list-inline list-justified sub-menu">

				<?php
				/** @var $service Service */
				foreach ($services as $service) {
					echo CHtml::openTag('li');

						$class = 'item-'.ltrim($service->color, '#');
						echo CHtml::openTag('span', array('class'=>$class));
							echo '<i>'.$service->name."</i>\n";

							echo CHtml::openTag('ul', array('class'=>'list-unstyled'));
							echo CHtml::tag('li', array('data-service'=>$service->id), 'Все направления')."\n";

							/** @var $direction Direction */
							foreach (Direction::getActiveByTime($currentMonth, $nextMonth, $service->id) as $direction) {
								echo CHtml::tag('li', array('data-id'=>$direction->id), $direction->name)."\n";
							}

							echo CHtml::closeTag('ul');
						echo CHtml::closeTag('span');

					echo CHtml::closeTag('li')."\n";
				}

//				?>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<ul class="list-inline filter-items">
				<?php
				if ( $model instanceof Service ) {
					echo CHtml::tag('li', array(), $model->name.' (все направления)<i></i>');
				} elseif ( $model instanceof Direction ) {
					echo CHtml::tag('li', array(), $model->name.'<i></i>');
				}
				?>
				<!-- <li>Хатха-йога для начинающих<i data-show="1"></i></li> -->
			</ul>
		</div>
	</div>
	<div class="row period-links">
		<div class="col-lg-6">
			<?php
			$monthNumber = date('n', $currentTime);
			$yearNumber = date('Y', $currentTime);
			$prevMonthTime = DateMap::prevMonth($currentTime);
			echo CHtml::link(DateMap::$monthMap[ date('n', $prevMonthTime) ],
				$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$prevMonthTime)),
				array('class'=>'prev-month', 'data-time'=>$prevMonthTime)
			);
			echo CHtml::tag('strong',
				array('class'=>'current', 'data-month'=>$monthNumber, 'data-year'=>$yearNumber),
				DateMap::$monthMap[$monthNumber].', '.$yearNumber
			);
			echo CHtml::link(DateMap::$monthMap[ date('n', $nextMonth) ],
				$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$nextMonth)),
				array('class'=>'next-month', 'data-time'=>$nextMonth)
			);
			?>
		</div>
		<div class="col-lg-6 ">
			<?php
			$prevWeek = DateMap::prevWeek($currentTime);
			$nextWeek = DateMap::nextWeek($currentTime);
			echo CHtml::link('Предыдущая неделя',
				$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$prevWeek)),
				array('class'=>'prev-month', 'data-time'=>$prevWeek)
			);

			echo CHtml::link('Следующая неделя',
				$this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$nextWeek)),
				array('class'=>'next-month', 'data-time'=>$nextWeek)
			);
			?>
		</div>
	</div>
	<div class="table-responsive first-table">
		<table class="table timeline-days">
			<thead>
				<tr>
					<div>
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
					</div>
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
						<?php
						// выбрано направление - недельный вид
						if ( Config::getIsWeekView($model) ) {
							$this->renderPartial('index/_weekEvents', array(
								'halls'=>$halls,
								'events'=>$events,
								'services'=>$allServices,
								'checkedTime'=>$currentTime,
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
					</td>
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
<!-- EOF PAGE CONTENT -->
<script>
	$(function () {
		/*теперь объект _moduleOptions содержит следующее:
		* center_id
		* type
		* item
		* search - true/false? в зависимости от того есть ли в url строка поиска
		* */
		Calendar.initialize(<?php echo json_encode(array(
			'center_id'=>$centerId,
			'type' => Config::$routeMap[$model::MODEL_TYPE],
			'item' => $model->id,
			'day'=>$currentTime,
			'search'=>!empty($_GET['search']),
		), JSON_NUMERIC_CHECK); ?>);
	});
</script>