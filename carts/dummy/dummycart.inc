<?php 

require_once 'config.inc';
require_once '../../lib/xmlrpc.inc';
session_start();

$xmlrpc_internalencoding = 'UTF-8';

function affiliate_call($method, $params) {
    global $rpc_secret, $affiliate_base;

    array_unshift($params, new xmlrpcval($rpc_secret, 'string'));
    $message = new xmlrpcmsg($method, $params);
    $client = new xmlrpc_client("$affiliate_base/xmlrpc-cart.php");
    $client->return_type = 'phpvals';

    // If you want to see the xmlrpc interaction, uncomment the following line:
    // $client->setDebug(2);

    $result = $client->send($message);
    if($result->faultCode()) {
        trigger_error('xmlrpc: ' .
            '(code ' . htmlentities($result->faultCode()) . ') ' .
            htmlentities($result->faultString()),
            E_USER_ERROR);
    } else {
        return $result->value();
    }
}

function wrap_string_struct(&$struct) {
    $result = array();
    foreach($struct as $key => $value)
        $result[$key] = new xmlrpcval($value, 'string');

    return new xmlrpcval($result, 'struct');
}

function get_cookie() {
    $response = affiliate_call('get_cookie', array(
        wrap_string_struct($_GET),
        wrap_string_struct($_COOKIE)));

    if(count($response) > 0)
        call_user_func_array('setcookie', $response);
}

function place_order() {
    global $new_order;

    // Don't place orders more than once.

    $orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : array();
    if(isset($orders[$_GET['orderno']])) {
        $new_order = FALSE;
    } else {
        $new_order = TRUE;
        $orders[$_GET['orderno']] = TRUE;
        $_SESSION['orders'] = $orders;

        affiliate_call('order_placed', array(
            wrap_string_struct($_GET),
            wrap_string_struct($_COOKIE),
            new xmlrpcval($_GET['orderno'], 'string'),
            new xmlrpcval($_GET['quantity'] * 5, 'double'),
            new xmlrpcval($_GET['email'], 'string'),
            new xmlrpcval($_GET['name'], 'string'),
            new xmlrpcval($_GET['customer'], 'string')));
    }
}

function ship_order() {
    affiliate_call('order_shipped', array(
        new xmlrpcval($_GET['orderno'], 'string')));
}

function cancel_order() {
    affiliate_call('order_cancelled', array(
        new xmlrpcval($_GET['orderno'], 'string')));
}

get_cookie();
