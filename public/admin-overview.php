<?php 

$admin_required = TRUE;
require_once '../lib/bootstrap.php';

$template = new Template('admin-overview');

$siteoverview = __('Site Overview');
$template->set('title', "$affiliate_programme_name: $siteoverview");
$template->set('currency', $currency);

$db = new Database();

if(isset($_FILES['file'])) {
    $success = 0;
    $failure = 0;
    $payments = file($_FILES['file']['tmp_name']);
    foreach($payments as $payment) {
        $fields = preg_split("/\t/", $payment);
        $identifiers = preg_split('/_/', $fields[3]);
        $id = $identifiers[1];
        if($fields[1] && $id) {
            $db->insert('payments',
                array('affiliate' => $id, 'amount' => $fields[1]));
            $success++;
        } else {
            $failure++;
        }
    }

    $template->set('message', '
        <div id="message" class="dialog" title="Payment Upload">
          Upload completed.  '.$success.' payments created, '.$failure.'
          errors.
        </div>');
} else {
    $template->set('message', '');
}

$stmt = $db->get_pdo()->query(
    'select sum(total), sum(commission) from orders ' .
    "where status in ('shipped', 'refunded', 'refund')");

$row = $stmt->fetch();
$template->set('total_orders', $row ? $db->format_currency($row[0]) : '0.00');
$template->set('total_commission',
    $row ? $db->format_currency($row[1]) : '0.00');

$pay_stmt = $db->get_pdo()->query('select sum(amount) from payments');
$pay_row = $pay_stmt->fetch();
$template->set('total_payments', $db->format_currency($pay_row[0]));
$template->set('total_payable', $db->format_currency($row[1] - $pay_row[0]));

$template->render();
