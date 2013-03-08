<?php 

require_once '../lib/bootstrap.php';

global $aes_key;

Template::check_ajax_key();

$db = new Database();
$result = $db->update_password('affiliates', $_SESSION['affiliate_id'],
    $_GET['password'], $aes_key);


echo json_encode($result ? true : false);
