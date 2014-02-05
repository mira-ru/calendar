<div class="modal-dialog modal-lightbox calendar-modal form">
	<div class="modal-content">
		<div class="modal-header"><button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">&times;</button></div>
		<div class="modal-body">
			<h4>Запись на занятие</h4>
			<div class="flow">
				<?php $form = $this->beginWidget('CActiveForm', array(
					'htmlOptions'=>array('class'=>'col-10')
				)); ?>
					<label class="flow">
						<strong class="col-5">Имя, Фамилия</strong>
						<?php echo $form->textField($model, 'name', array('class'=>'col-7')); ?>
						<div class="clearfix"></div>
					</label>
					<label class="flow">
						<strong class="col-5">Телефон</strong>
						<?php echo $form->textField($model, 'phone', array('class'=>'col-7')); ?>
						<div class="clearfix"></div>
					</label>
					<label class="flow">
						<strong class="col-5">E-mail</strong>
						<?php echo $form->textField($model, 'email', array('class'=>'col-7')); ?>
						<div class="clearfix"></div>
					</label>
					<?php echo $form->hiddenField($model, 'eventId'); ?>
					<input type="submit" value="Записаться" class="-button">
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>
