<h2>Личные сообщения - исходящие</h2>

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

