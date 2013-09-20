<?php
/**
 * @var $centers array
 * @var $current Center
 * @var $checkedTime integer
 * @var $events array
 * @var $halls array
 * @var $services array
 */
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
				<?php
				$cnt = 1;
				/** @var $center Center */
				foreach ($centers as $center) {
					$class = 'item-'.$cnt;
					if ($center->id == $current->id) {
						$class .= ' current';
						$url = 'javascript:void(0)';
					} else {
						$url = $this->createUrl('/site/index', array('id'=>$center->id));
					}
					echo CHtml::tag('li', array('class'=>$class),
						CHtml::link($center->name, $url)
					)."\n";
					$cnt++;
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
							echo $service->name."\n";

							echo CHtml::openTag('ul', array('class'=>'list-unstyled'));
							/** @var $direction Direction */
							foreach ($service->directions as $direction) {
								echo CHtml::tag('li', array('data-id'=>$direction->id), $direction->name)."\n";
							}
								echo CHtml::tag('li', array('data-id'=>0), 'Все направления')."\n";

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
				<!-- <li>Хатха-йога для начинающих<i data-show="1"></i></li> -->
			</ul>
		</div>
	</div>
	<div class="table-responsive">
		<?php
		$monthNumber = date('n', $checkedTime);
		$yearNumber = date('Y', $checkedTime);
		$prevMonthTime = DateMap::getPrevMonth($checkedTime);
		$nextMonthTime = DateMap::getNextMonth($checkedTime);
		$currentMonthTime = DateMap::currentMonth($checkedTime);
		
		echo CHtml::link(DateMap::$monthMap[ date('n', $prevMonthTime) ],
			$this->createUrl('/site/index', array('id'=>$current->id, 'time'=>$prevMonthTime)),
			array('class'=>'prev-month')
		);
		echo CHtml::tag('strong',
			array('class'=>'current', 'data-month'=>$monthNumber, 'data-year'=>$yearNumber),
			DateMap::$monthMap[$monthNumber].', '.$yearNumber
		);
		echo CHtml::link(DateMap::$monthMap[ date('n', $nextMonthTime) ],
			$this->createUrl('/site/index', array('id'=>$current->id, 'time'=>$nextMonthTime)),
			array('class'=>'next-month')
		);
		?>
		<table class="table timeline-days">
			<thead>
			<tr>
				<?php
				$daysOfMonth = date('t', $checkedTime);
				$dayNumber = date('j', $checkedTime);
				for ($n=1; $n<=$daysOfMonth; $n++) {
					echo CHtml::openTag('td');

					$htmlOptions = array();
					if ($n == $dayNumber) {
						$htmlOptions['class'] = 'current';
					}
					echo CHtml::openTag('span', $htmlOptions);

					$dayTime = $currentMonthTime + ($n-1)*86400;
					$dow = date('w', $dayTime);

					$htmlOptions = array(
						'data-weekday'=>DateMap::$smallDayMap[$dow],
						'data-day'=>$dayTime,
					);
					if ($dow == 0 || $dow == 6) {
						$htmlOptions['class'] = 'weekend';
					}

					echo CHtml::tag('i', $htmlOptions, $n);

					echo CHtml::closeTag('span');
					echo CHtml::closeTag('td');
				}



				?>
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
					<?php
					$this->renderPartial('_events', array('halls'=>$halls, 'events'=>$events, 'services'=>$services));
					?>
					<p class="warning-empty">К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!</p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
</div>
<!-- EOF PAGE CONTENT -->
<script>
	$(function () {
		Calendar.initialize(<?php echo json_encode(array('center_id'=>$current->id), JSON_NUMERIC_CHECK); ?>);
	});
</script>