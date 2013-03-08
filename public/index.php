<?php 

$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';

$template = new Template('index');
$template->suppress_menu();

$welcome = __('Welcome');
$template->set('title', "$affiliate_programme_name: $welcome");

$template->render();

