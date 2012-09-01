<?php
if ($this->_userId == $data->recipient_id) 
{
	$data->markAsRead();
}
?>
<div class="view">
	<div style="float: left;">
		[<?php echo Yii::app()->dateFormatter->format('dd MMMM yyyy hh:mm',$data->created); ?>] 
		<b><?php echo $data->sender->name; ?></b> 
		<?php echo $this->_userId == $data->sender_id? '&larr;' : '&rarr;' ?>
		&nbsp;
	</div>
	<div>
		<?php echo nl2br(CHtml::encode($data->text)); ?>
	</div>
</div>

