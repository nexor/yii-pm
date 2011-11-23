<h2><?php echo PmModule::t('Personal messages'); ?>  - <?php echo PmModule::t('View message'); ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array('data' => $model)); ?>

<?php echo CHtml::link(PmModule::t('Reply'), array('/pm/default/reply', 'id' => $model->id));?>

<?php echo CHtml::link(PmModule::t('Delete'), '#', array(
	'submit' => array('/pm/default/delete', 'id' => $model->id),
	'confirm' => PmModule::t('Do you really want to delete this message?')	
)); ?>
	
