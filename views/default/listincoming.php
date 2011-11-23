<h2><?php echo PmModule::t('Personal messages'); ?> - <?php echo PmModule::t('Incoming'); ?></h2>

<?php if (Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
       <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

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




