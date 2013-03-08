<?php 

$wizard_not_required = TRUE;
require_once '../lib/bootstrap.php';

$template = new Template('account');

$title = __('Account Settings');
$template->set('title', "$affiliate_programme_name: $title");

$template->set('terms', $terms_of_business);
$template->set('wizard', isset($_SESSION['wizard_incomplete']));
if (isset($_SESSION['parent_id'])) {
    $template->set('parent_id', $_SESSION['parent_id']);
} else {
    $template->set('parent_id', null);
}
if(isset($_SESSION['wizard_incomplete']))
    $template->suppress_menu();

$db = new Database();
$stmt = $db->get_pdo()->query(
    'select * from affiliates where id = ' . $_SESSION['affiliate_id']);

$row = $stmt->fetch();
foreach($row as $key => $value)
    $template->set("user_$key", $value);

if ($template->get("user_payment_check") == 1) {
    $template->set("check_checked", 'checked');
    $template->set("paypal_checked", '');
} else {
    $template->set("paypal_checked", 'checked');
    $template->set("check_checked", '');
}


$stmt = $db->get_pdo()->query(
    'select * from countries order by name');

$template->set("countries", $stmt->fetchAll());

$template->render();
