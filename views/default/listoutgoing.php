<h2><?php echo PmModule::t('Personal messages'); ?> - <?php echo PmModule::t('Outgoing'); ?></h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		'recipient',
		array(
			'name' => 'date',
			'value' => 'Yii::app()->dateFormatter->format("yyy-MM-dd HH:mm:ss", $data->date)'
		),
		'subject',
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{delete}'
		)
	)	
)); ?>

