<?php 

$admin_required = TRUE;
require_once '../lib/bootstrap.php';

global $return_days;

$db = new Database();
//$stmt = $db->get_pdo()->prepare(
//    'select id, paypal, local_username, (
//        select sum(commission) from orders
//        where orders.affiliate=affiliates.id
//            and orders.status in (\'refund\', \'refunded\', \'shipped\')
//            and orders.date_entered < :date + interval 1 day
//        ) as commission, (
//            select sum(amount) from payments
//            where payments.affiliate=affiliates.id
//        ) as already_paid from affiliates');

$stmt = $db->get_pdo()->prepare('select id, paypal, local_username, (
        select sum(commission) from orders
        where orders.affiliate=affiliates.id
            and orders.status in (\'refund\', \'refunded\', \'shipped\')
            and orders.date_entered < :date + interval 1 day
            and orders.date_entered + interval :return_days day <= :date
         ) as commission,(
             select sum(parent_commission) from orders
             where orders.parent=affiliates.id
                and orders.status in (\'refund\', \'refunded\', \'shipped\')
                and orders.date_entered + interval :return_days day <= :date
            ) as parent_commission, (
               select sum(amount) from payments
               where payments.affiliate=affiliates.id) as already_paid from affiliates');



$date = Database::format_date($_GET['end']);
$stmt->execute(array('date' => $date,
                'return_days' => $return_days));
$rows = $stmt->fetchAll();

header('Content-Type: text/plain');
foreach($rows as $row) {
    $amount = sprintf('%2.2f', $row[3] + $row[4] - $row[5]);
    $id = preg_replace('/[^A-Za-z0-9]/', '', $row[2]);
    if(strlen($id) > 15)
        $id = substr($id, 0, 15);
    if($amount > 0)
        echo "${row[1]}\t$amount\t$currency_code\taff_${row[0]}_$id\t" .
            "Your affiliate commission to $date.  Thank you.\n";
}
