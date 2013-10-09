<?php
/**
 * @var $item Direction
 * @var $image ImageComponent
 */
$image = Yii::app()->image;
?>
<div class="modal-dialog modal-lightbox calendar-modal">
	<div class="modal-content">
		<div class="modal-header"><button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">&times;</button></div>
		<div class="modal-body">
			<div class="row">
				<div class="col-lg-4">
					<div class="master-photo"></div><?php
						echo CHtml::image(
							$image->getPreview($item->image_id, 'crop_240')
							, ''
							, array('class'=>'img-circle', 'width'=>240, 'height'=>240)
						);
					?>
					<div class="text-center">
						<span>Запись по телефону:</span>
						<h2 class="phone green"><?php echo Yii::app()->params->miraPhone; ?></h2>
						<?php /*
						<span class="clearfix">Наши мастера:</span>
						<img src="/images/src/master.jpg" class="img-circle master-thumb" width="75" height="75">
						<a href="/site/axMasterInfo" class="green clearfix">Екатерина Силкачева</a>
						<img src="/images/src/master.jpg" class="img-circle master-thumb" width="75" height="75">
						<a href="/site/axMasterInfo" class="green clearfix">Екатерина Силкачева</a>
 */ ?>
					</div>
				</div>
				<div class="col-lg-8">
					<?php echo CHtml::tag('h1', array(), $item->name); ?>
					<?php echo $item->desc; ?>
					<h4><strong>Стоимость занятий</strong></h4>
					<?php echo $item->price; ?>
    					<div id="disqus_thread"></div>
    					<script>
					dsq.onload = function() {
						DISQUS.reset({
							reload: true,
							config: function () {
								this.page.identifier = "action-<?php echo $item->id; ?>";
							}
						});
					}
					</script>
				</div>
			</div>
		</div>
	</div>
</div>