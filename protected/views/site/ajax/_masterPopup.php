<?php
/**
 * @var $item User
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
							$image->getPreview($item->image_id, 'crop_240', User::DEFAULT_IMG)
							, ''
							, array('class'=>'img-circle', 'width'=>240, 'height'=>240)
						);
					?>
					<div class="text-center">
						<span>Запись по телефону:</span>
						<h2 class="phone green"><?php echo Yii::app()->params->miraPhone; ?></h2>
					</div>
				</div>
				<div class="col-lg-8">
					<?php
					$h1 = $item->name;
					if (!Yii::app()->getUser()->getIsGuest()) {
						$h1 .= CHtml::link(''
							, $this->createUrl('/admin/user/update', array('id'=>$item->id))
							, array('class'=>'pencil', 'target'=>'_blank'));
					}
					echo CHtml::tag('h1', array(), $h1);
					?>
					<?php echo $item->desc; ?>
    					<div id="disqus_thread"></div>
    					<script>
					resetDisqus = function(){
						DISQUS.reset({
							reload: true,
							config: function () {
								this.page.identifier = "master-<?php echo $item->id; ?>";
							}
						});
					};
					if (typeof DISQUS === 'undefined') {
						window.onload = resetDisqus;
					} else {
						resetDisqus();
					}
					</script>
				</div>
			</div>
		</div>
	</div>
</div>