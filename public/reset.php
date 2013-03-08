<?php 
$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';

global $publickey;

$template = new Template('reset');

$template->suppress_menu();

$reset = __('Reset Password');
$template->set('title', "$affiliate_programme_name: $reset");
$template->set('publickey', $publickey);

$template->render();
