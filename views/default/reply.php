<h2><?php echo PmModule::t('Personal messages'); ?> - <?php echo PmModule::t('Reply to message'); ?></h2>

<div style="border: 1px dashed #444444; padding: 10px;">
	<h3><?php echo CHtml::encode($model->subject); ?></h3>
	<p><?php echo Yii::app()->dateFormatter->format('d MMMM yy, HH:mm', $model->date); ?></p>
	<p><?php echo nl2br(Chtml::encode($model->text)); ?></p>
</div>

<?php $this->renderPartial('_form', array('model' => $modelNew)); ?>

