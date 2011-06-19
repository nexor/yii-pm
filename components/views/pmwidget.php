<?php 
if ($unread)
	echo CHtml::link('Личные сообщения(новых: '.$unread.')',
		Yii::app()->controller->createUrl('/pm/default')
	);
else
	echo CHtml::link('Личные сообщения',
		Yii::app()->controller->createUrl('/pm/default'));
?>
