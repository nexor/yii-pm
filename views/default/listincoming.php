<h2>Личные сообщения - входящие</h2>

<?php 
if (Yii::app()->user->hasFlash('success')) {
	?>
       <?php echo Yii::app()->user->getFlash('success'); ?>
<?php
} ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		'id',
		'sender',
		array(
			'name' => 'read',
			'value' => '$data->read?"yes":"no"'
		),
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
));?>




