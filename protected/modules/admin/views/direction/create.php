<?php
/* @var $this DirectionController */
/* @var $model Direction */

?>

<h1>Добавление услуги</h1>

<?php $this->renderPartial('_form', array(
			'model'=>$model,
			'centers'=>$centers,
			'services'=>$services,
)); ?>