<?php

require_once '../lib/bootstrap.php';

$template = new Template('orders');

$yourorders = __('Your Orders');
$template->set('title', "$affiliate_programme_name: $yourorders");

$template->render();
