<?php 
if ($unread)
	echo CHtml::link(PmModule::t('Messages({unread})', array('{unread}' => $unread)),
		array('/pm/default/listincoming')
	);
else
	echo CHtml::link(PmModule::t('Messages'), array('/pm/default/listincoming'));
?>
