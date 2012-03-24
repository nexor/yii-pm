<?php
$this->breadcrumbs += array(PmModule::t('View message'));
?>

<h2><?php echo PmModule::t('View message'); ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		array(
			'name' => 'sender_id',
			'type' => 'text',
			'value' => $model->senderName
		),
		array(
			'name' => 'recipient_id',
			'type' => 'text',
			'value' => $model->recipientName
		),
		'read:boolean',
		'ds:boolean',
		'dr:boolean',
		'created',
		'subject:text',
		'text:ntext'
	)
)); ?>

<?php if ($this->_userId != $model->sender_id)
	echo CHtml::link(PmModule::t('Reply'), array('/pm/default/reply', 'id' => $model->id));
?>

<?php echo CHtml::link(PmModule::t('Delete'), '#', array(
	'submit' => array('/pm/default/delete', 'id' => $model->id),
	'confirm' => PmModule::t('Do you really want to delete this message?')	
)); ?>
	
