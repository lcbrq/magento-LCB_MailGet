<?php

/**
 * 
 * @category   LCB
 * @package    LCB_MailGet
 * @author     Silpion Tomasz Gregorczyk <tom@lcbrq.com>
 * @author     Marcin Gierus <martin@lcbrq.com>
 */
class LCB_MailGet_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function isEnabled()
    {
        return Mage::getStoreConfig('mailget/connection/enable');
    }
    
}
