<?php
$this->breadcrumbs += array(PmModule::t('Compose message')); 
?> 

<h2><?php echo PmModule::t('Compose message'); ?></h2>

<p>
	<?php echo PmModule::t('Compose message for user'); echo ' '.CHtml::encode($model->recipientName); ?>
</p>

<?php $this->renderPartial('_form', array('model' => $model)); ?>

