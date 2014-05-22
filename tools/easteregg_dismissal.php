<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * tool for permanental dismissal of the easteregg
 * (c) 2014 PasswordX
 * Apache v2 License
 */

$u->saveConfig('permanent_easteregg_dismissal', 1);

exit();