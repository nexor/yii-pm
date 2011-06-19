<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'personal-message-form'
)); ?>
		
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'subject'); ?>
		<?php echo $form->textField($model, 'subject', array('size' => 50)); ?>
		<?php echo $form->error($model, 'subject'); ?>	
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'text'); ?>
		<?php echo $form->textArea($model, 'text', array(
			'rows' => 10,
			'cols' => 60
		)); ?>
		<?php echo $form->error($model, 'text'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(PmModule::t('Отправить')); ?>
	</div>
<?php $this->endWidget(); ?>
</div>
