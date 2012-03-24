<?php
$this->breadcrumbs += array(PmModule::t('View conversation'));
?>
<h2><?php echo PmModule::t('View conversation with {user}', array('{user}' => $message->recipientName)); ?></h2>

<?php 
$data = array_reverse($dataProvider->getData());
$dataProvider->setData($data);

$this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_message',
	'template' => '{items}'
)); ?>

<?php $this->renderPartial('_form', array(
	'model' => $message
)); ?>
	
