<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var DefaultController $this
 */
$this->breadcrumbs += array(PmModule::t('Outgoing'));
?>
<h2><?php echo PmModule::t('Outgoing'); ?></h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		array(
			'name' => 'recipient_id',
			'value' => 'Yii::app()->controller->module->getUserName($data->recipient);'
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

