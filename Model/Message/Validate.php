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

namespace Ecomteck\Core\Model\Message;

use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\UrlInterface;
use Ecomteck\Core\Helper\Validate as ValidateHelper;

/**
 * Class Validate
 * @package Ecomteck\Core\Model\Message
 */
class Validate implements MessageInterface
{
    /**
     * @var \Ecomteck\Core\Helper\Validate
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $_needActiveModules = [];

    /**
     * Validate constructor.
     * @param \Ecomteck\Core\Helper\Validate $helper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        ValidateHelper $helper,
        UrlInterface $urlBuilder
    )
    {
        $this->_helper    = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        if (empty($this->_needActiveModules)) {
            $moduleLists = $this->_helper->getModuleList();
            foreach ($moduleLists as $module) {
                if ($this->_helper->needActive($module) && !$this->_helper->isModuleActive($module)) {
                    $this->_needActiveModules[] = $module;
                }
            }
        }

        return $this->_needActiveModules;
    }

    /**
     * Check whether all extensions are valid or not
     *
     * @return bool
     */
    public function isDisplayed()
    {
        $notActiveModules = $this->getModules();
        if (sizeof($notActiveModules)) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve unique message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return md5('ecomteck_VALIDATE_MESSAGE');
    }

    /**
     * Retrieve message text
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getText()
    {
        $modules = $this->getModules();
        if (empty($modules)) {
            return '';
        }

        $sectionName = $this->_helper->getConfigModulePath($modules[0]);
        $url         = $this->urlBuilder->getUrl('adminhtml/system_config/edit', ['section' => $sectionName]);

        return __(
            'One or more Ecomteck extensions are not validated. Click <a href="%1">here</a> to validate them.',
            $url
        );
    }

    /**
     * Retrieve message severity
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}
