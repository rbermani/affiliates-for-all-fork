<?php 

require_once '../lib/bootstrap.php';

$pager = new OrderPager('orders', $order_fields_available, $order_fields_headings);

if($_GET['format'] == 'download') {
    echo $pager->download();
} else {
    Template::check_ajax_key();
    echo $pager->json($_GET['page']);
}
