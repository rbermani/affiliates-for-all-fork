<?php 

class Afa_Helper_Data extends Mage_Core_Helper_Abstract {
    const XML_URL = 'afa/afa/url';
    const XML_SECRET = 'afa/afa/secret';

    public function getUrl() {
        return Mage::getStoreConfig(self::XML_URL);
    }

    public function getSecret() {
        return Mage::getStoreConfig(self::XML_SECRET);
    }
}
