<?php
$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';

Template::check_ajax_key();


if(isset($_GET['confirmcode']) && isset($_GET['email'])){

    $db = new Database();
    $result = $db->get_row_by_key('affiliates', 'email', $_GET['email']);

    if (isset($result)){

        $time = time();
        // 86,400 = number of seconds in one day
        $entry_timestamp = $result['reset_stamp'] + 86400;

        if ($entry_timestamp > $time && ($result['confirm_code'] == $_GET['confirmcode'])){
            // time stamp has not expired yet and confirm code matches database
            // allow password change
            echo json_encode(1);
        } else if ($entry_timestamp < $time){
            // time stamp is expired
            // report expired time stamp
            echo json_encode(2);
        } else if ($result['confirm_code'] != $_GET['confirmcode']){
            echo json_encode(3);
        }
    } else {
        echo json_encode(4);
    }
unset($db);
}


