<?php
/**
 *
 * @category   LCB
 * @package    LCB_MailGet
 * @author     Silpion Tomasz Gregorczyk <tom@lcbrq.com>
 * @author     Marcin Gierus <martin@lcbrq.com>
 */

require_once(Mage::getBaseDir('lib') . '/MailGet/mailget_curl.php');

class LCB_MailGet_Model_Api
{

    const LOG_FILE = "mailget.log";

    protected $_apiKey;
    protected $_apiList;
    
    public function __construct()
    {
        $this->_apiKey = Mage::getStoreConfig('mailget/connection/key');
        $this->_apiList = Mage::getStoreConfig('mailget/connection/list');
    }

    /**
     * Add email to MailGet
     *
     * @param string $email
     * @param string $name
     * @return boolean
     */
    public function addEmail($name, $email)
    {
        $mailgetKey = $this->_apiKey;
        $sendVal = 'multiple';
        $mailgetObj = new mailget_curl($mailgetKey);
        $listArr = $mailgetObj->get_list_in_json($mailgetKey);
        $listId = '';

        if (!empty($listArr)) {
            foreach ($listArr as $listArrRow) {
                if ($listArrRow->list_name ==  $this->_apiList) {
                    $listId = $listArrRow->list_id;
                    break;
                }
            }

            /**
             * Get the user IP Address
             */
            $visitorData = Mage::getSingleton('core/session')->getVisitorData();
            $remoteAddr = Mage::helper('core/http')->getRemoteAddr(true);

            $mailget_arr = array(

                array(
                    'name' => $name,
                    'email' => $email,
                    'get_date' => date('Y-m-d H:i:s'),
                    'ip' => $remoteAddr
                ),
            );
            
            if ($listId != '') {
                $curlStatus = $mailgetObj->curl_data($mailget_arr, $listId, $sendVal);
                Mage::log($curlStatus, null, self::LOG_FILE, true);
                if ($curlStatus) {
                    return true;
                }
            }
        }

        return false;

    }

}
