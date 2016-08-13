<?php

/**
 * 
 * @category   LCB
 * @package    LCB_MailGet
 * @author     Silpion Tomasz Gregorczyk <tom@lcbrq.com>
 * @author     Marcin Gierus <martin@lcbrq.com>
 */
class LCB_MailGet_Model_Observer {

    /**
     * Subscribe to MailGet on Magento newsletter submission
     * 
     * @param Varien_Event_Observer $observer
     */
    public function newsletterSubscribe(Varien_Event_Observer $observer)
    {
        if (Mage::helper('mailget')->isEnabled()) {
            $subscriber = $observer->getEvent()->getSubscriber();
            Mage::getModel('mailget/api')->addEmail('Newsletter Subscriber', $subscriber->getEmail());
        }
    }

    /**
     * Autosubscribe customer to MailGet after order is placed 
     * 
     * @param Varien_Event_Observer $observer
     */
    public function orderPlace(Varien_Event_Observer $observer)
    {
        if (Mage::helper('mailget')->isEnabled() && Mage::getStoreConfig('mailget/settings/autosubscribe')) {
            $order = $observer->getEvent()->getOrder();
            Mage::getModel('mailget/api')->addEmail($order->getCustomerName(), $order->getCustomerEmail());
        }
    }

}
