<?php
/**
 * @var $template EventTemplate
 */
?>
<ul id="event-users">
	<?php
	$users = $template->getUsers();
	foreach($users as $uid) {
		$user = User::model()->findByPk($uid);
		if ($user===null) { continue; }
		echo CHtml::tag('li',
			array(),
			'<a href="#">[x]</a>'.$user->name.CHtml::hiddenField('EventTemplate[users][]', $user->id)
		);
	}
	?>
</ul>
<script type="text/javascript">
	$('#event-users').on('click', 'a', function(){
		$(this).parent().remove();
	});
</script>