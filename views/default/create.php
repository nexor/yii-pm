<h2><?php echo PmModule::t('Personal messages'); ?> - <?php echo PmModule::t('Compose message'); ?></h2>

<p><?php echo PmModule::t('Compose message for user');?> #<?php echo $model->recipient; ?></p>

<?php $this->renderPartial('_form', array('model' => $model)); ?>


