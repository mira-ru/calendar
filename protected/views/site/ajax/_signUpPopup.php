<div class="modal-dialog modal-lightbox calendar-modal form">
	<div class="modal-content">
		<div class="modal-header"><button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">&times;</button></div>
		<div class="modal-body">
			<h4 class="col-12">Запись на занятие</h4>
			<div class="flow">
				<?php $form = $this->beginWidget('CActiveForm', array(
					'htmlOptions'=>array('class'=>'col-12')
				)); ?>
					<div class="flow">
						<label class="col-6">
							<strong>Ваше имя и фамилия <span class="required">*</span></strong>
							<?php echo $form->textField($model, 'name', array('class'=>'col-12')); ?>
							<div class="clearfix"></div>
						</label>
						<label class="col-6">
							<strong>Контактный телефон <span class="required">*</span></strong>
							<?php echo $form->textField($model, 'phone', array('class'=>'col-12')); ?>
							<div class="clearfix"></div>
						</label>
					</div>
					<div class="flow">
						<label class="col-12">
							<strong>Адрес электронной почты</strong>
							<?php echo $form->textField($model, 'email', array('class'=>'col-12')); ?>
							<div class="clearfix"></div>
						</label>
					</div>
					<div class="flow">
						<label class="col-12">
							<strong>Комментарий</strong>
							<?php echo $form->textArea($model, 'comment', array('class'=>'col-12')); ?>
							<div class="clearfix"></div>
						</label>
					</div>
					<div class="">
						<label>
							<?php echo $form->checkBox($model, 'is_first'); ?> Я записываюсь впервые
						</label>
					</div>
					<div class="">
						<label>
							<?php echo $form->checkBox($model, 'is_need_consult'); ?> Мне нужна консультация администратора центра
						</label>
					</div>
					<?php echo $form->hiddenField($model, 'eventId'); ?>
					<input type="submit" value="Записаться" class="-button">
					<br>
					<div>Дети до 14 лет посещают центр в сопровождении взрослых</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>