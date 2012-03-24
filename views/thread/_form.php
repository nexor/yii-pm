<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'personal-message-form'
)); ?>
		
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'text'); ?>
		<?php echo $form->textArea($model, 'text', array(
			'rows' => 6,
			'cols' => 80
		)); ?>
		<?php echo $form->error($model, 'text'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(PmModule::t('Send')); ?>
	</div>
<?php $this->endWidget(); ?>
</div>
