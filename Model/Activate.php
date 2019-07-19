<?php
/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_Core
 * @copyright   Copyright (c) 2018 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

namespace Ecomteck\Core\Model;

use Ecomteck\Core\Helper\AbstractData;

/**
 * Class Activate
 * @package Ecomteck\Core\Model
 */
class Activate extends \Magento\AdminNotification\Model\Feed
{
    /**
     * @inheritdoc
     */
    const ECOMTECK_ACTIVE_URL = 'https://ecomteck.com/license-activate.php';

    /**
     * @inheritdoc
     */
    public function getActiveUrl()
    {
        return self::ECOMTECK_ACTIVE_URL;
//        $httpPath = $this->_backendConfig->isSetFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://';
//        if ($this->_feedUrl === null) {
//            $this->_feedUrl = $httpPath . self::ECOMTECK_ACTIVE_URL;
//        }
//
//        return $this->_feedUrl;
    }

    /**
     * @param array $params
     * @return array
     */
    public function activate($params = [])
    {
        $result = ['success' => false];

        $curl = $this->curlFactory->create();
        $curl->write(\Zend_Http_Client::POST, $this->getActiveUrl(), '1.1', [], http_build_query($params));
        try {
            $resultCurl = $curl->read();
            if (!empty($resultCurl)) {
                $responseBody = \Zend_Http_Response::extractBody($resultCurl);
                $result       += AbstractData::jsonDecode($responseBody);
                if (isset($result['status']) && in_array($result['status'], [200, 201])) {
                    $result['success'] = true;
                }
            } else {
                $result['message'] = __('Cannot connect to server. Please try again later.');
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }

        $curl->close();

        return $result;
    }
}
