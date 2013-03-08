<?php 

class Afa_Observer {
    private $url, $secret, $new_order;

    public function __construct() {
        $this->url = Mage::getStoreConfig('afa/afa/url');
        $this->secret = Mage::getStoreConfig('afa/afa/secret');
        $this->new_order = FALSE;
    }

    private function affiliate_call($method, $params) {
        array_unshift($params, $this->secret);

        try {
            $client = new Zend_XmlRpc_Client("{$this->url}/xmlrpc-cart.php");
            return $client->call($method, $params);
        } catch(Zend_XmlRpc_Client_HttpException $e) {
            Mage::log('AfA xmlrpc: (code ' . $e->getCode() . ') ' .
                $e->getMessage(), Zend_Log::WARN);

            return FALSE;
        } catch (Zend_XmlRpc_Client_FaultException $e) {
            Mage::log('AfA xmlrpc: (code ' . $e->getCode() . ') ' .
                $e->getMessage(), Zend_Log::WARN);

            return FALSE;
        }
    }

    public function set_affiliate_cookie($observer) {
        $response = $this->affiliate_call('get_cookie', array(
            Zend_XmlRpc_Value::getXmlRpcValue(
                $_GET, Zend_XmlRpc_Value::XMLRPC_TYPE_STRUCT),
            Zend_XmlRpc_Value::getXmlRpcValue(
                $_COOKIE, Zend_XmlRpc_Value::XMLRPC_TYPE_STRUCT)));

        if($response && count($response) > 0)
            call_user_func_array('setcookie', $response);

        return $this;
    }

    public function order_placed($observer) {
        $this->new_order = TRUE;
        return $this;
    }

    public function model_saved($observer) {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        if($this->new_order) {
            $customer_id = (string) $order->getCustomerId();
            if($customer_id == '0' /* checkout as guest */)
                $customer_id = '';

            $this->affiliate_call('order_placed', array(
                Zend_XmlRpc_Value::getXmlRpcValue(
                    $_GET, Zend_XmlRpc_Value::XMLRPC_TYPE_STRUCT),
                Zend_XmlRpc_Value::getXmlRpcValue(
                    $_COOKIE, Zend_XmlRpc_Value::XMLRPC_TYPE_STRUCT),
                (string) $order->getRealOrderId(),
                (string) ($order->getData('subtotal') -
                    $order->getData('discount_amount')),
                $order->getData('customer_email'),
                $order->getCustomerName(),
                $customer_id,
                $order->getData('total_qty_ordered')));
        }

        if($order->getStatus() == "complete") {
            $this->affiliate_call('order_shipped',
                array((string) $order->getRealOrderId()));
        }

        return $this;
    }
}
