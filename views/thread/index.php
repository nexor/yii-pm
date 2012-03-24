<h1><?php echo PmModule::t('Personal messages'); ?></h1>
<?php if (Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php elseif (Yii::app()->user->hasFlash('error')): ?>
	<div class="flash-error">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
<?php endif; ?>

<p><?php echo PmModule::t('Unread messages');?>: <?php echo (int)$unread; ?></p>

<p><?php echo PmModule::t('Compose message'); ?>:</p>
<ul>
	<?php foreach ($users as $user): ?>
	<li> 
		<?php echo $user->{$this->module->getNameMethod}(); ?>
		&nbsp;
		(<?php echo CHtml::link(PmModule::t('Compose message'), array(
			'view', 'id' => $user->getPrimaryKey())
		); ?>)
	</li>
	<?php endforeach; ?>
</ul>

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'columns' => array(
		array(
			'name' => 'interlocutorId',
			'type' => 'text',
			'value' => '$data->getInterlocutorName()'
		),
		'read:boolean',
		'created',
		'subject:text',
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}',
			'viewButtonUrl' => 'array("view", "id" => $data->interlocutorId)'
		)
	),
	'template' => '{items}'
));?>

<br />
<h2><?php echo PmModule::t('Widget example'); ?></h2>
<?php $this->widget('application.modules.pm.components.pmwidget', array(
	'url' => array('/pm/thread/unreadList')	
)); ?>
