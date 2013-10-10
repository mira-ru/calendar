<?php
/**
 * @var $centers array
 * @var $current Center
 * @var $checkedTime integer
 * @var $events array
 * @var $halls array
 * @var $services array
 * @var $activeDays array
 * @var $currentMonth integer
 * @var $nextMonth integer
 * @var $serviceId integer
 * @var $directionId integer
 * @var $checkedDirection Direction
 */
?>
<!-- PAGE CONTENT -->
<script>
	Calendar.reloadWithHash();
</script>
<div id="wrap" class="<?php echo (!empty($directionId)) ? 'week-view' : '';?>">
<div class="container">
	<div class="row">
		<div class="col-lg-12 text-center">
			<h1>Расписание</h1>
		</div>
		<div class="col-lg-12">
			<ul class="list-inline list-justified top-menu">
				<?php
				/** @var $center Center */
				foreach ($centers as $center) {
					$class = 'menu-'.ltrim($center->color, '#');

					if ($center->id == $current->id) {
						$class .= ' current';
						$url = 'javascript:void(0)';
					} else {
						$url = $this->createUrl('/site/index', array('center_id'=>$center->id, 'time'=>$checkedTime));
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
				if (!empty($serviceId)) {
					$service = $services[$serviceId];
					echo CHtml::tag('li', array(), $service->name.' (все направления)<i></i>');
				} elseif (!empty($checkedDirection)) {
					echo CHtml::tag('li', array(), $checkedDirection->name.'<i></i>');
				}
				?>
				<!-- <li>Хатха-йога для начинающих<i data-show="1"></i></li> -->
			</ul>
		</div>
	</div>
	<div class="row period-links">
		<div class="col-lg-6">
			<?php
			$monthNumber = date('n', $checkedTime);
			$yearNumber = date('Y', $checkedTime);
			$prevMonthTime = DateMap::prevMonth($checkedTime);
			echo CHtml::link(DateMap::$monthMap[ date('n', $prevMonthTime) ],
				$this->createUrl('/site/index', array('center_id'=>$current->id, 'time'=>$prevMonthTime, 'direction_id'=>$directionId, 'service_id'=>$serviceId)),
				array('class'=>'prev-month', 'data-time'=>$prevMonthTime)
			);
			echo CHtml::tag('strong',
				array('class'=>'current', 'data-month'=>$monthNumber, 'data-year'=>$yearNumber),
				DateMap::$monthMap[$monthNumber].', '.$yearNumber
			);
			echo CHtml::link(DateMap::$monthMap[ date('n', $nextMonth) ],
				$this->createUrl('/site/index', array('center_id'=>$current->id, 'time'=>$nextMonth, 'direction_id'=>$directionId, 'service_id'=>$serviceId)),
				array('class'=>'next-month', 'data-time'=>$nextMonth)
			);
			?>
		</div>
		<div class="col-lg-6 ">
			<?php
			$prevWeek = DateMap::prevWeek($checkedTime);
			$nextWeek = DateMap::nextWeek($checkedTime);
			echo CHtml::link('Предыдущая неделя',
				$this->createUrl('/site/index', array('center_id'=>$current->id, 'time'=>$prevWeek, 'direction_id'=>$directionId, 'service_id'=>$serviceId)),
				array('class'=>'prev-month', 'data-time'=>$prevWeek)
			);

			echo CHtml::link('Следующая неделя',
				$this->createUrl('/site/index', array('center_id'=>$current->id, 'time'=>$nextWeek, 'direction_id'=>$directionId, 'service_id'=>$serviceId)),
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
					if (!empty($directionId)) {
						$this->renderPartial('index/_daysWeek',
							array('checkedTime'=>$checkedTime, 'activeDays'=>$activeDays)
						);
					} else {
						$this->renderPartial('index/_daysMonth',
							array('checkedTime'=>$checkedTime, 'activeDays'=>$activeDays)
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
						if (!empty($directionId)) {
							$this->renderPartial('index/_weekEvents', array(
								'halls'=>$halls,
								'events'=>$events,
								'services'=>$services,
								'checkedTime'=>$checkedTime,
								'centerId'=>$current->id,
								'serviceId'=>$serviceId,
								'directionId'=>$directionId,
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
		Calendar.initialize(<?php echo json_encode(array(
			'center_id'=>$current->id,
			'activity_id'=>$directionId,
			'service_id'=>$serviceId,
			'day'=>$checkedTime,
		), JSON_NUMERIC_CHECK); ?>);
	});
</script>