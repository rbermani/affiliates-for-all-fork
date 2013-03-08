<?php
$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';
global $aes_key;

Template::check_ajax_key();

$db = new Database();
$row = $db->get_row_by_key('affiliates', 'email', $_GET['user']);

if($row){

    $_SESSION['affiliate_id'] = $row['id'];

    $pass_check = $db->check_password_by_key('affiliates',
        $row['id'], $_GET['password'], $aes_key);
    $pass_check_fetch = $pass_check->fetch();

    if(($pass_check_fetch != null) && ($pass_check != false)) {
        $login_retries = 0;
        echo json_encode('overview.php');

        if(!$row['wizard_complete'])
            $_SESSION['wizard_incomplete'] = TRUE;

        if($row['administrator']) {
            $_SESSION['administrator'] = TRUE;
            $_SESSION['administrator_id'] = $row['id'];
        } else {
            $_SESSION['administrator'] = FALSE;
            unset($_SESSION['administrator_id']);
        }

        if($row['parent_id']) {
            $_SESSION['parent_id'] = $row['parent_id'];
            $row2 = $db->get_row_by_key('affiliates', 'id', $row['parent_id']);
            $_SESSION['parent_username'] = $row2['email'];
        }
    } else {
       $login_retries = $row['login_retries'];
       $login_retries = (isset($login_retries) ? $login_retries+1 : 1);

    }

$db->update_by_key('affiliates', 'id', $_SESSION['affiliate_id'],
    array('login_retries' => $login_retries));

}

if (!isset($row) || !isset($pass_check_fetch)) {
    echo json_encode(false);
}

unset($db);