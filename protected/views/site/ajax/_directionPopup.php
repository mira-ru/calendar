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
			<div class="flow">
				<div class="col-4">
					<?php
						echo CHtml::image(
							$image->getPreview($item->image_id, 'crop_240')
							, ''
							, array('class'=>'img-round', 'width'=>240, 'height'=>240)
						);
					?>
					<div class="master-photo"></div>
					<div class="text-center">
						<span>Запись по телефону:</span>
						<h2 class="phone green"><?php echo Yii::app()->params->miraPhone; ?></h2>
						<?php //$this->widget('application.components.widgets.SignUpButtonWidget', array('event'=>$event)); ?>
						<?php /*
						<span class="clearfix">Наши мастера:</span>
						<img src="/images/src/master.jpg" class="img-circle master-thumb" width="75" height="75">
						<a href="/site/axMasterInfo" class="green clearfix">Екатерина Силкачева</a>
						<img src="/images/src/master.jpg" class="img-circle master-thumb" width="75" height="75">
						<a href="/site/axMasterInfo" class="green clearfix">Екатерина Силкачева</a>
 */ ?>
					</div>
				</div>
				<div class="col-8">
					<?php
					$h1 = $item->name;
					if (!Yii::app()->getUser()->getIsGuest()) {
						$h1 .= CHtml::link(''
							, $this->createUrl('/admin/direction/update', array('id'=>$item->id))
							, array('class'=>'pencil', 'target'=>'_blank'));
					}
					echo CHtml::tag('h1', array(), $h1);
					?>
					<?php echo $item->desc; ?>
					<?php
					if (!empty($item->url)) {
						echo CHtml::openTag('div', array('class'=>'additional-services'));
						echo StrUtil::videoUrlConvert($item->url);
						echo CHtml::closeTag('div');
					}
					if (!empty($item->photo_url)) {
						echo CHtml::openTag('div', array('class'=>'additional-services'));
						echo StrUtil::videoUrlConvert($item->photo_url);
						echo CHtml::closeTag('div');
					}
					?>
					<?php
					if (!empty($item->price)) {
						echo CHtml::tag('h4', array(), '<strong>Стоимость</strong>');
						echo $item->price;
					}
					?>
    					<div id="disqus_thread"></div>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    					<script>
					resetDisqus = function(){
						DISQUS.reset({
							reload: true,
							config: function () {
								this.page.identifier = "direction-<?php echo $item->id; ?>";
								this.page.url = "<?php echo Yii::app()->homeUrl.'/#!direction-'.$item->id; ?>";
							}
						});
					};
					if (typeof DISQUS === 'undefined') {
						//window.onload = resetDisqus;
						var disqus_shortname = 'miracentr';
						var disqus_identifier = 'direction-<?php echo $item->id; ?>';
						var disqus_url = "<?php echo Yii::app()->homeUrl.'/#!direction-'.$item->id; ?>";
						var disqus_developer = '1';
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true; dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					} else {
						resetDisqus();
					}
					// Учитывание целей Яндекс-счетчиком
					yaCounter22425796.reachGoal('direction');
					</script>
				</div>
			</div>
		</div>
	</div>
</div>