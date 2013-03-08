<?php
$admin_required = TRUE;
require_once '../lib/bootstrap.php';

function validate_record($fields) {
    $db = new Database();

    if(!$db->get_row_by_key('affiliates', 'id', $fields['affiliate'])) {
        echo json_encode('That affiliate does not exist.');
        return FALSE;
    }

    if($db->get_row_by_key('orders', 'id', $fields['id'])
            && (!isset($fields['key']) || $fields['id'] != $fields['key'])) {
        echo json_encode('That order number is already in use.');
        return FALSE;
    }

    return TRUE;
}

$pager = new Pager('orders', Database::$order_fields,Database::$order_headings);
$pager->set_admin_mode();
$pager->set_editable();

if($_GET['format'] == 'download') {
    $pager->download();
} else if($_GET['format'] == 'json') {
    Template::check_ajax_key();
    $pager->json($_GET['page']);
} else if($_GET['format'] == 'write') {
    Template::check_ajax_key();
    if(validate_record($_GET))
        $pager->write($_GET);
} else if($_GET['format'] == 'delete') {
    Template::check_ajax_key();
    $pager->delete($_GET['key']);
} else {
    Template::check_ajax_key();
    $pager->json_single($_GET['key']);
}
