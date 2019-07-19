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

namespace Ecomteck\Core\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AbstractData
 * @package Ecomteck\Core\Helper
 */
class Validate extends AbstractData
{
    /**
     * @var array
     */
    protected $configModulePath = [];

    /**
     * @var array
     */
    protected $_ecomteckModules;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * Validate constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        ModuleListInterface $moduleList
    )
    {
        $this->_moduleList = $moduleList;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function needActive($moduleName)
    {
        $type = $this->getModuleType($moduleName);
        if (!$type || !in_array($type, ['1', '2'])) {
            return false;
        }

        return true;
    }

    /**
     * @param $moduleName
     * @return mixed
     */
    public function getModuleType($moduleName)
    {
        $configModulePath = $this->getConfigModulePath($moduleName);
        return $this->getConfigValue($configModulePath . '/module/type');
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function isModuleActive($moduleName)
    {
        $configModulePath = $this->getConfigModulePath($moduleName);

        return $this->getConfigValue($configModulePath . '/module/active')
            && $this->getConfigValue($configModulePath . '/module/product_key');
    }

    /**
     * @param $moduleName
     * @return array
     */
    public function getModuleCheckbox($moduleName)
    {
        $configModulePath = $this->getConfigModulePath($moduleName);

        $create = $this->getConfigValue($configModulePath . '/module/create');
        if (is_null($create)) {
            $create = 1;
        }

        $subscribe = $this->getConfigValue($configModulePath . '/module/subscribe');
        if (is_null($subscribe)) {
            $subscribe = 1;
        }

        return [
            'create'    => (int) $create,
            'subscribe' => (int) $subscribe
        ];
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function getConfigModulePath($moduleName)
    {
        if (!isset($this->configModulePath[$moduleName])) {
            $this->configModulePath[$moduleName] = false;

            $helperClassName = str_replace('_', '\\', $moduleName) . '\Helper\Data';
            if (class_exists($helperClassName)) {
                $helper = $this->objectManager->get($helperClassName);
                if ($helper instanceof AbstractData) {
                    $this->configModulePath[$moduleName] = $helper::CONFIG_MODULE_PATH;
                }
            }
        }

        return $this->configModulePath[$moduleName];
    }

    /**
     * @return array
     */
    public function getModuleList()
    {
        if (is_null($this->_ecomteckModules)) {
            $this->_ecomteckModules = [];

            $moduleList = $this->_moduleList->getNames();
            foreach ($moduleList as $name) {
                if (strpos($name, 'Ecomteck_') === false) {
                    continue;
                }

                $this->_ecomteckModules[] = $name;
            }
        }

        return $this->_ecomteckModules;
    }
}