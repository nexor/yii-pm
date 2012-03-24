<?php
	$this->breadcrumbs += array(PmModule::t('Reply to message')); 
?>
<h2><?php echo PmModule::t('Reply to message'); ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'subject:text',
		'created',
		'senderName:text',
		'text:ntext'
	)
)); ?>

<?php $this->renderPartial('_form', array('model' => $modelNew)); ?>

