<?php 

$event_file = DIR_BASE . '/libraries/event_handler.php';

//on user added
Events::extend(
	'on_user_add',
	'EventHandler',
	'user_added',
	$event_file
);

//on user updated
Events::extend(
	'on_user_update',
	'EventHandler',
	'user_updated',
	$event_file
);