<?php 

$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';
global $aes_key;

Template::check_ajax_key();

$db = new Database();
$parent_user = $_GET['parent_user'];
$parent_id = null;

if ($parent_user != ''){
    // $parent_user is not empty, determine parent_id
    $row1 = $db->get_row_by_key('affiliates', 'email', $parent_user);

    if (isset($row1)){
        $parent_user = $row1['email'];
        $parent_id = $row1['id'];
    }

}
unset($row1);

$insertcheck = $db->insert('affiliates', array(
    'email' => $_GET['user'],
    'local_username' => $_GET['user'],
    'local_password' => $_GET['password'],
    'parent_id' => $parent_id));

if(isset($insertcheck)) {

    $row = $db->get_row_by_key('affiliates', 'email', $_GET['user']);
    if (isset($row)){
        $db->update_password('affiliates', $row['id'], $_GET['password'], $aes_key);

        $_SESSION['affiliate_id'] = $row['id'];
        $_SESSION['wizard_incomplete'] = true;
        $_SESSION['administrator'] = false;


        echo json_encode('overview.php');
    } else
    echo json_encode(false);

} else {
    echo json_encode(false);
}

unset($db);

