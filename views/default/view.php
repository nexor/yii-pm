<h2>Личные сообщения - просмотр сообщения</h2>

<?php $this->widget('zii.widgets.CDetailView', array('data' => $model)); ?>

<a href="<?php echo $this->createUrl('/pm/default/reply', array('id' => $model->id));?>"
	><?php echo PmModule::t('Ответить'); ?></a>

<?php echo CHtml::link(PmModule::t('Удалить'), '#', array(
	'submit' => array('/pm/default/delete', 'id' => $model->id),
	'confirm' => PmModule::t('Вы действительно хотите удалить это сообщение?')	
)); ?>
	
