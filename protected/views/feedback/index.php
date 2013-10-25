<?php
/* @var $this FeedbackController */

$this->breadcrumbs=array(
	'Feedback',
);
?>


<div class="grid">
	<div class="flow">
		<div class="col-6 skip-3 pass-3">
			<div class="feedback-form">
				<h1 class="title">Книга отзывов и предложений</h1>
				<div id="form">
					<form id="feedbackForm">
						<label class="radio"><input type="radio" value=0 name="status" checked><span>я хочу предложить</span></label>
						<label class="radio"><input type="radio" value=1 name="status"><span>я хочу поблагодарить</span></label>
						<label class="radio"><input type="radio" value=2 name="status"><span>я хочу пожаловаться</span></label>
						<label>
							<!-- <span class="block">следующее:</span> -->
							<textarea rows="10" placeholder="Написать что-нибудь очень желательно" name="text"></textarea>
						</label>
						<label>
							<span>Меня зовут:</span>
							<input type="text" name="name" placeholder="Можно не представляться">
						</label>
						<div class="submit">
							<button type="submit"></button>
						</div>
					</form>
				</div>
				<div id="tabs">
					<a data-status=0 href="#"><i>Предложения</i></a>
					<a data-status=1 href="#" class="current"><i>Благодарности</i></a>
					<a data-status=2 href="#"><i>Жалобы</i></a>
				</div>
				<div id="list">
					<div>
						<h1>Аноним <span class="red">жалуется</span>:</h1>
						<p>«Текст»</p>
					</div>
					<div>
						<h1>logonarium <span class="orange">предлагает</span>:</h1>
						<p>«Текст»</p>
					</div>
					<div>
						<h1>Аноним <span class="green">благодарит</span>:</h1>
						<p>«Текст»</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>