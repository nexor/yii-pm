<?php
	$this->breadcrumbs += array(PmModule::t('Incoming')); 
?>
<h2><?php echo PmModule::t('Incoming'); ?></h2>

<?php if (Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
       <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		array(
			'name' => 'sender_id',
			'type' => 'text',
			'value' => '$data->senderName'
		),
		'read:boolean',
		'created',
		'subject:text',
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{delete}'
		)
	)
));?>
