<?php 

require_once '../lib/bootstrap.php';

$template = new Template('payments');

$yourpayments = __('Your Payments');
$template->set('title', "$affiliate_programme_name: $yourpayments");

$template->render();
