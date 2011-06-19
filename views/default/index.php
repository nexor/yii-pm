<h1><?php echo PmModule::t('Личные сообщения'); ?></h1>
<?php if (Yii::app()->user->hasFlash('success')): ?>
<p style="border: 2px solid green;">Result: <?php echo Yii::app()->user->getFlash('success'); ?></p>
<?php elseif (Yii::app()->user->hasFlash('success')): ?>
<p style="border: 2px solid red">Error: <?php echo Yii::app()->user->getFlash('error'); ?></p>
<?php endif; ?>

<p>Новых сообщений: <?php echo (int)$unread; ?></p>

<p><a href="<?php echo $this->createUrl('/pm/default/listincoming'); ?>"><?php echo PmModule::t('Полученные'); ?></a></p>
<p><?php echo CHtml::link(PmModule::t('Отправленные'), $this->createUrl('/pm/default/listoutgoing')); ?></p>
<?php $form = $this->beginWidget('CActiveForm', array(
	'action' => $this->createUrl('/pm/default/create'),
	'method' => 'get'
)); ?>
	User id:
	<?php
	    
		echo CHtml::textField('to');
        echo Chtml::submitButton(PmModule::t('Новое сообщение'));
	?>
<?php $this->endWidget(); ?>
