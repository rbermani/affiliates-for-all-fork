<?php 

$wizard_not_required = TRUE;
require_once '../lib/bootstrap.php';

Template::check_ajax_key();

$db = new Database();
$result = $row = $db->update_by_key('affiliates',
    'id', $_SESSION['affiliate_id'],
    array('paypal' => $_GET['paypal'],
    'wizard_complete' => TRUE,
    'payment_check' => $_GET['checkchecked']));

unset($_SESSION['wizard_incomplete']);

echo json_encode($result ? true : false);
