<?php 

$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';

// The XMLRPC library goes wrong if the error reporting is too strict.
error_reporting(E_ALL);

require_once 'xmlrpc.inc';
require_once 'xmlrpcs.inc';

$xmlrpc_internalencoding = 'UTF-8';
/**
 *
 * @global <type> $rpc_secret
 * @global <type> $xmlrpcerruser
 * @param <type> $secret
 * @return bool
 */
function check_secret($secret) {
    global $rpc_secret, $xmlrpcerruser;

    if($secret != $rpc_secret)
        return new xmlrpcresp(0, $xmlrpcerruser + 1,
            'Cart integration passed the wrong RPC secret.  Please check ' .
            'config.inc and your cart settings.');

    return FALSE;
}

function get_cookie($secret, $get, $cookie) {
    global $affiliate_referrer_parameter, $affiliate_data_parameter,
        $affiliate_cookie, $cookie_lifetime, $cookie_domain;

    if($error = check_secret($secret))
        return $error;

    if(isset($get[$affiliate_referrer_parameter])) {
        $data = $get[$affiliate_referrer_parameter] . ',';
        
        if(isset($get[$affiliate_data_parameter]))
            $data .= substr($get[$affiliate_data_parameter], 0, 256);

        $result = array($affiliate_cookie, $data,
            time() + 60 * 60 * 24 * $cookie_lifetime, '/');

        if($cookie_domain != '')
            $result[] = $cookie_domain;

        return $result;
    } else {
        return array();
    }
}

function order_placed($secret, $get, $cookie, $order_no, $amount, $email,
        $name, $customer_id, $order_qty) {

    global $affiliate_cookie, $commission_percent, $commission_fixed,
        $lifetime_revenue_share;

    if($error = check_secret($secret))
        return $error;

    $affiliate = null;
    $db = new Database();

    if(isset($cookie[$affiliate_cookie]))
        $affiliate = preg_split('/,/', $cookie[$affiliate_cookie], 2);

    if($affiliate == null && $lifetime_revenue_share && $customer_id != '') {
        $stmt = $db->get_pdo()->prepare(
            'select affiliate, affiliate_data from orders ' .
            'where customer_id = :customer_id ' .
            'order by date_entered desc limit 1');

        $stmt->execute(array('customer_id' => $customer_id));
        $row = $stmt->fetch();
        if($row)
            $affiliate = $row;
    }

    if($affiliate != null) {
        $row = $db->get_row_by_key('affiliates', 'id', $affiliate[0]);
        if(isset($row)) {
            $fixed = $commission_fixed * $order_qty;
            $percent = $commission_percent;
            $fixedparent = $commission_parent_fixed * $order_qty;
            $percentparent = $commission_parent_percent;

            if(!$row['default_commission']) {
                $fixed = $row['commission_fixed'];
                $percent = $row['commission_percent'];
                $fixedparent = $row['commission_parent_fixed'];
                $percentparent = $row['commission_parent_percent'];
            }
            
            $commission = $amount * $percent / 100 + $fixed;
            $commission_parent = $amount * $parentpercent / 100 + $fixedparent;


            if ($row['parent_id'] != null){
            $data = array(
                'id' => $order_no,
                'affiliate' => $affiliate[0],
                'parent' => $row['parent_id'],
                'affiliate_data' => $affiliate[1],
                'total' => $amount,
                'commission' => $commission,
                'parent_commission' => $commission_parent,
                'status' => 'new',
                'customer_email' => $email,
                'customer_name' => $name,
                'customer_id' => $customer_id);
            } else {
            $data = array(
                'id' => $order_no,
                'affiliate' => $affiliate[0],
                'affiliate_data' => $affiliate[1],
                'total' => $amount,
                'commission' => $commission,
                'status' => 'new',
                'customer_email' => $email,
                'customer_name' => $name,
                'customer_id' => $customer_id);
            }


            $db->insert('orders', $data);

        }
    }

    unset($db);
}

function order_shipped($secret, $order_no) {
    if($error = check_secret($secret))
        return $error;

    $db = new Database();
    $order = $db->get_row_by_key('orders', 'id', $order_no);
    if($order['status'] == 'new') {
        $db->update_by_key('orders', 'id', $order_no,
            array('status' => 'shipped'));
    }
}

function order_cancelled($secret, $order_no) {
    if($error = check_secret($secret))
        return $error;

    $db = new Database();
    $order = $db->get_row_by_key('orders', 'id', $order_no);
    if($order['status'] == 'new') {
        $db->update_by_key('orders', 'id', $order_no,
            array('status' => 'cancelled'));
    } else if($order['status'] == 'shipped') {
        $db->update_by_key('orders', 'id', $order_no,
            array('status' => 'refunded'));

        if ($order['parent_commission'] != null) {
        $new_order = array(
            'id' => $order['id'] . '-r',
            'affiliate' => $order['affiliate'],
            'parent' => $order['parent'],
            'affiliate_data' => $order['affiliate_data'],
            'status' => 'refund',
            'customer_email' => $order['customer_email'],
            'customer_name' => $order['customer_name'],
            'total' => -$order['total'],
            'commission' => -$order['commission'],
            'parent_commission' => -$order['parent_commission']);

        } else {
        $new_order = array(
            'id' => $order['id'] . '-r',
            'affiliate' => $order['affiliate'],
            'affiliate_data' => $order['affiliate_data'],
            'status' => 'refund',
            'customer_email' => $order['customer_email'],
            'customer_name' => $order['customer_name'],
            'total' => -$order['total'],
            'commission' => -$order['commission']);
        }

        $db->insert('orders', $new_order);
    }

    unset($db);
}

$server = new xmlrpc_server(array(
    'get_cookie' => array('function' => 'get_cookie'),
    'order_placed' => array('function' => 'order_placed'),
    'order_shipped' => array('function' => 'order_shipped'),
    'order_cancelled' => array('function' => 'order_cancelled')), 0);

$server->functions_parameters_type = 'phpvals';
$server->service();
