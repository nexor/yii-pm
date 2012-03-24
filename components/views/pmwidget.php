<?php 
if ($unread)
	echo CHtml::link(PmModule::t('Messages({unread})', array('{unread}' => $unread)),
		$this->url
	);
else
	echo CHtml::link(PmModule::t('Messages'), $this->url);
?>
