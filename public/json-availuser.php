<?php 
$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';

Template::check_ajax_key();

$user = $_GET['user'];
$parent = $_GET['parent_user'];
$arr = array();

$db = new Database();

if ($parent != ''){
    $row2 = $db->get_row_by_key('affiliates', 'email', $parent);

    if ($row2 == null) {
        $arr[2] = false;
        $arr[3] = '<div class="no">‘'.$parent.'’ does not exist.</div>';
    } else {
        $arr[2] = true;
        $arr[3] = '<div class="yes">‘'.$parent.'’ user account found.</div>';
    }
} else {
    $arr[2] = false;
}


if($user == '') {
    $arr[0] = false;
    $arr[1] = '<div class="no">Please enter a username.</div>';

} else {
    
    $row = $db->get_row_by_key('affiliates', 'email', $user);

    if($row == null) {
        $arr[0] = true;
        $arr[1] = '<div class="yes">‘'.$user.'’ is available.</div>';
    } else {
        $arr[0] = false;
        $arr[1] = '<div class="no">‘'.$user.'’ is not available.</div>';
    }
    
}
    echo json_encode($arr);

