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

<ul>
	<li>
		<?php echo CHtml::link(PmModule::t('Incoming'), array('/pm/default/listincoming')); ?>
	</li>
	<li>
		<?php echo CHtml::link(PmModule::t('Outgoing'), array('/pm/default/listoutgoing')); ?>
	</li>
</ul>

<p>10 пользователей:</p>
<ul>
	<?php foreach ($users as $user): ?>
	<li> 
		<?php echo $user->{$this->module->getNameMethod}(); ?>
		&nbsp;
		(<?php echo CHtml::link('Написать сообщение', array(
			'create', 'to' => $user->getPrimaryKey())
		); ?>)
	</li>
	<?php endforeach; ?>
</ul>

<?php $form = $this->beginWidget('CActiveForm', array(
	'action' => $this->createUrl('/pm/default/create'),
	'method' => 'get'
)); ?>
	<?php echo PmModule::t('User id'); ?>:
	<?php
	    
		echo CHtml::textField('to');
        echo CHtml::submitButton(PmModule::t('Compose message'), array('name' => false));
	?>
<?php $this->endWidget(); ?>

<br />
	<h2><?php echo PmModulle::t('Widget example'); ?></h2>
<?php $this->widget('application.modules.pm.components.pmwidget'); ?>
