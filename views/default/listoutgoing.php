<?php
	$this->breadcrumbs += array(PmModule::t('Outgoing')); 
?>
<h2><?php echo PmModule::t('Outgoing'); ?></h2>

<?php 
$getNameMethod = Yii::app()->getModule('pm')->getNameMethod;
//$recipientValue = 


$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		array(
			'name' => 'recipient_id',
			'value' => '$data->recipient->'.$getNameMethod.'();'
		),
		array(
			'name' => 'created',
			'value' => 'Yii::app()->dateFormatter->format("yyy-MM-dd HH:mm:ss", $data->created)'
		),
		'subject',
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{delete}'
		)
	)	
)); ?>

