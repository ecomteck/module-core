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

/**
 * Class Feed
 * @package Ecomteck\Core\Model
 */
class Feed extends \Magento\AdminNotification\Model\Feed
{
    /**
     * @inheritdoc
     */
    const ecomteck_FEED_URL = 'www.ecomteck.com/notifications.xml';

    /**
     * @inheritdoc
     */
    public function getFeedUrl()
    {
        $httpPath = $this->_backendConfig->isSetFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://';
        if ($this->_feedUrl === null) {
            $this->_feedUrl = $httpPath . self::ecomteck_FEED_URL;
        }

        return $this->_feedUrl;
    }

    /**
     * @inheritdoc
     */
    public function checkUpdate()
    {
        if (!(boolean)$this->_backendConfig->getValue('ecomteck/general/notice_enable')) {
            return $this;
        }

        return parent::checkUpdate();
    }

    /**
     * @inheritdoc
     */
    public function getFeedData()
    {
        $type = $this->_backendConfig->getValue('ecomteck/general/notice_type');
        if (!$type) {
            return false;
        }

        $feedXml = parent::getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            $typeArray    = explode(',', $type);
            $noteToRemove = [];

            foreach ($feedXml->channel->item as $item) {
                if (!in_array((string)$item->type, $typeArray)) {
                    $noteToRemove[] = $item;
                }
            }
            foreach ($noteToRemove as $item) {
                unset($item[0]);
            }
        }

        return $feedXml;
    }

    /**
     * @inheritdoc
     */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load('ecomteck_notifications_lastcheck');
    }

    /**
     * @inheritdoc
     */
    public function setLastUpdate()
    {
        $this->_cacheManager->save(time(), 'ecomteck_notifications_lastcheck');

        return $this;
    }

}
