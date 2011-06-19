<h2>Личные сообщения - новое сообщение</h2>

<p>Новое сообщение для пользователя #<?php echo $model->recipientUser->{$this->module->useridField}; ?></p>

<?php $this->renderPartial('_form', array('model' => $model)); ?>


