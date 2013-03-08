<?php 
$admin_required = TRUE;
require_once '../lib/bootstrap.php';

Template::check_ajax_key();

$pager = new Pager('affiliates', Database::$affiliate_short_fields,
    Database::$affiliate_short_headings, Database::$affiliate_fields);
$pager->set_admin_mode();
$pager->set_editable();
$pager->set_order_by('local_username');
$pager->disable_date_restriction();

if($_GET['format'] == 'json') {
    $pager->json($_GET['page']);
} else if($_GET['format'] == 'write') {
    // Don't let people lock themselves out of the administration screens:
    if($_GET['key'] == 1)
        $_GET['administrator'] = 1;

    $pager->write($_GET);
} else if($_GET['format'] == 'delete') {
    // Don't let people delete the Admin account:
    if($_GET['key'] != 1)
        $pager->delete($_GET['key']);
} else {
    $pager->json_single($_GET['key']);
}
