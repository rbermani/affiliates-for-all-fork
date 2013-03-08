<?php 

$wizard_not_required = TRUE;
require_once '../lib/bootstrap.php';

Template::check_ajax_key();

parse_str($_GET['details'], $details);

// Check the user isn't trying to change anything he isn't supposed to:

$junk = array_diff_key($details, array_fill_keys(array(
    'title', 'first_name', 'last_name', 'email', 'email_update', 'address1',
    'address2', 'address3', 'address4', 'postcode', 'country', 'phone'), true));

if(count($junk) == 0) {
    $db = new Database();
    $details['email_update'] = isset($details['email_update']);
    $result = $db->update_by_key('affiliates',
        'id', $_SESSION['affiliate_id'], $details);

    echo json_encode($result ? true : false);
}
