<?php

/**
 * 
 * @category   LCB
 * @package    LCB_MailGet
 * @author     Silpion Tomasz Gregorczyk <tom@lcbrq.com>
 * @author     Marcin Gierus <martin@lcbrq.com>
 */
class LCB_MailGet_Model_Observer {

    public function newsletterSubscribe(Varien_Event_Observer $observer)
    {
        $subscriber = $observer->getEvent()->getSubscriber();
        Mage::getModel('mailget/api')->addEmail('Newsletter Subscriber',$subscriber->getEmail());
    }

    public function orderPlace(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        Mage::getModel('mailget/api')->addEmail($order->getCustomerName(),$order->getCustomerEmail());
    }

}
